<?php

namespace api\modules\v3_1_1\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CorrectService;
use common\service\CommonFuncService;
use common\service\dict\RankService;

/**
 * 获取分页排行榜数据
 * @author Administrator
 *
 */
class RankpageAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn)
            $rn = 10;
        $rankType = $this->requestParam('ranktype', true); //获取排行版类型 1日榜 2周榜 3月榜单
        $correctType = $this->requestParam('correcttype', true); //批改类型 1:色彩   4:素描    5:速写
        $year = $this->requestParam('year') ? $this->requestParam('year') : date('Y'); //年 新接口必须要传递
        if ($rankType == 1) {
            $strlen = strlen($this->requestParam('timetype'));
            if ($strlen == 4) {
                $timestamp = $this->requestParam('timetype') ? strtotime($year . $this->requestParam('timetype')) : strtotime(date('Y-m-d'));
            } else {
                $timestamp =strtotime(date('Y-m-d'));
            }
        } elseif ($rankType == 2) {
            $timestamp = $this->requestParam('timetype') ? $this->requestParam('timetype') : date("W");
        } elseif ($rankType == 3) {
            $timestamp = $this->requestParam('timetype') ? $this->requestParam('timetype') : date("m");
        }
        $lastid = $this->requestParam('lastid'); //分页
        $status = $this->requestParam('status'); //点击搜索时要传递的参数
        $ret['content'] = (array) [];
        $data = CorrectService::getNewRank($correctType, $rankType, $rn, $lastid, $this->_uid, false, $timestamp, $year, $status);  //获取数据
        $ids = $data['data'];
        if ($ids) {
            foreach ($ids as $key => $value) {
                $ret['content'][] = CorrectService::getFullCorrectInfo($value, $this->_uid);
            }
        }
        //如果首次获取数据时存在用户的最高分，就显示在最顶部
        if ($this->_uid) {
            foreach ($ret['content'] as $key => $val) {
                if ($val['submituid'] == $this->_uid) {
                    $my_score = $val['score'];
                    $correctid = $val['correctid'];
                    $my_url = $val['source_pic']['img']->l['url'];
                    $rank_key = $key+1;
                }
            }
        }
        $result = CorrectService::getUserRank($year, $rankType, $timestamp, $this->_uid, $correctType,$my_score);
        if ($result['max_score'] < 80) {//计算在美院帮已经提交的批改数量来获取称呼
            $num = CorrectService::getUserCorrectNum($this->_uid);
            if ($num < 5) {
                $info = 1;
            } else if ($num >= 5 && $num < 10) {
                $info = 2;
            } else if ($num >= 10 && $num < 20) {
                $info = 3;
            } else if ($num >= 20 && $num < 50) {
                $info = 4;
            } else if ($num >= 50 && $num < 100) {
                $info = 5;
            } else if ($num >= 100 & $num < 150) {
                $info = 6;
            } else if ($num > 150) {
                $info = 7;
            }
        } else if ($result['max_score'] >= 80 && $result['max_score'] < 85) {
            $info = 8;
        } else if ($result['max_score'] >= 85 && $result['max_score'] < 90) {
            $info = 9;
        } else if ($result['max_score'] >= 90) {
            $info = 10;
        }
        if ($result['max_score']) {
            if($result['correctid']){
                $img_date = CorrectService::getFullCorrectInfo($result['correctid'], $this->_uid)['source_pic']['img']->l['url'];
                $correctid = $result['correctid'];
            }else{
                $img_date =$my_url;
            }
            $url = '/rank/share/index?year=' . date('Y') . '&ranktype=' . $rankType . '&correctType=' . $correctType . '&timetype=' . $timestamp . '&correctid=' .$correctid. '&uid=' . $this->_uid;
            $ret['user_rank'] = [
                'share_url' => Yii::$app->params['sharehost'] . $url,
                'share_title' => '我的这张作品竟然拿到了'.$result['max_score'].'的分数,不服来战',
                'share_desc' => '快来参加美院帮线上名师改画，还能赢取精美奖品',
                'correct_img' =>$img_date,
                'max_score' => (int)isset($my_score)?$my_score:$result['max_score'],
                'appellation' =>(int)$info,
                'out_rand' => round($result['out_rand'] * 100), #碾压
                'score_rank_new' => (int)isset($rank_key)?$rank_key:$result['score_rank_new'], #最新排名
                'score_rank_past' => (int)$result['score_rank_past'],#昨天的分数
                'rand_forward' => (int)$result['rand_forward'] #昨天的减去今天的 = 进步的名称
            ];
        }
        $ret['user_appellation'] = RankService::getUserAppellation(); //称呼
        if (!$lastid) {//分页获取数据时不再返回周列表
            $ret['weeks'] = [[
            'week' => (string) date('Y'),
            'dateTime' => CommonFuncService::getWeek(date('Y')),
                ],[
            'week' => (string) (date('Y') - 1),
             'dateTime' => CommonFuncService::getWeek(date('Y') - 1)
            ]];
            //组装数据格式
            foreach ($ret['weeks'] as $ks => $val) {
                foreach ($val['dateTime'] as $kkk => $vvv) {
                    $ret['weekes'][$ks]['year'] = (int) $val['week'];
                    $ret['weekes'][$ks]['weekinfo'][] = [
                        'week' => $kkk,
                        'statr_time' => $vvv[0],
                        'end_time' => $vvv[1]
                    ];
                }
            }
            unset($ret['weeks']);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
