<?php

namespace api\modules\v3_0_2\controllers\live;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LiveService;
use api\service\UserDetailService;

class GetRefreshInfoAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
        $liveid = $this->requestParam('liveid', true); //直播id 
        $cid = $this->requestParam('first_id', true); //评论列表最小id
        $redis = Yii::$app->cache;
        if ($liveid) {
            $redis_key = 'comment_list_' . $liveid;
            $min = time()* -1;
            $ret = $redis->zrangebyscore($redis_key, $min, '+inf', [0, 100]);
            $arr = [];
            if (array_search($cid, $ret)) {
                foreach ($ret as $k => $v) {
                    if ($v > $cid) {
                        $arr[$k] = $v;
                    }
                }
            }
            $dataArray = [];
            if (!empty($arr)) {
                foreach ($arr as $kk => $vv) {
                    $dataArray[] = LiveService::getCommentDetaid($vv);
                }
            } else {
                if (empty($cid)) {
                    $dataArray = LiveService::getCommentList($liveid);
                }
            }
            if ($dataArray) {
                //不为空
                foreach ($dataArray as $k => $vvs) {
                    $dataArray[$k]['type'] = 0;
                }
                $keyBag = array_keys($dataArray, max($dataArray));
            }
           
            //黑名单
            $rediskeyList = "black_user_list_" . $liveid;
            $mlist = $redis->get($rediskeyList);
            if ($mlist) {
                $array = json_decode($mlist, 1);
                foreach ($array as $key => $val) {
                    if ($val['content'] == 80 && $val['cid']) {
                        $array[$key]['cid'] = isset($dataArray[$keyBag[0]]['cid'])?$dataArray[$keyBag[0]]['cid']:$cid; //最大的评论数cid
                        $array[$key]['type'] = 1; //暂时禁言
                        $array[$key]['content'] = '用户' . UserDetailService::getUserName($val['uid'])['sname'] . '暂时禁言';
                    } else if (($val['content'] == 1000000000 || $val['content'] == 2000000000)) {
                        $array[$key]['type'] = 2; //永久禁言
                        $array[$key]['cid'] = isset($dataArray[$keyBag[0]]['cid'])?$dataArray[$keyBag[0]]['cid']:$cid; //最大的评论数cid
                        $array[$key]['content'] = '用户' . UserDetailService::getUserName($val['uid'])['sname'] . '被永久禁言'; //永久禁言
                    }
                }
            } else {
                $array = [];
            }
            
            $newArray = array_merge($array, $dataArray);
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $newArray);
        } else {
            $ret = [];
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
        }
    }

}
