<?php

namespace api\service;

use Yii;
use common\models\myb\DkPrizeGame;
use common\models\myb\DkPrizeGamePrizes;
use common\models\myb\DkActivity;
use common\models\myb\DkPrizeUser;
use common\models\myb\DkPrizeShareRecord;
use api\service\UserCoinService;

/**
 * 
 * @author hjs
 * 抽奖
 *
 */
class DkService extends DkPrizeShareRecord {

    /**
     * 获取分享记录
     * @param $activeid int 活动id
     * @return array 查看分享记录表中是否有分享记录
     */
    static public function getPrizeShareRecord($activeid, $uid) {
        $connection = \Yii::$app->db;
        //今天零点时间
        $startTime = strtotime(date("Y-m-d"));
        //今天结束时间
        $endTime = strtotime(date("Y-m-d 23:59:59"));
        $command = $connection->createCommand('select count(*) count from dk_prize_share_record where activityid=' . $activeid . ' and uid=' . $uid . ' and status=1 and ctime between ' . $startTime . ' and ' . $endTime);
        $data = $command->queryAll();
        return $data[0];
    }

    /**
     * 获取大珈改画活动中对应的抽奖活动
     * @param $activeid int  活动id
     * @param $type     int  标签
     * @return array 查询抽奖活动对应活动的记录主键
     */
    static public function getPrizeGame($activeid, $type = 1) {
        $connection = \Yii::$app->db;
        if ($type == 1) {
            $command = $connection->createCommand("select gameid from dk_activity where activityid={$activeid} limit 1");
        } else {
            //查找活动记录
            $command = $connection->createCommand("select count(*) as count from dk_activity where activityid={$activeid} limit 1");
        }
        $data = $command->queryAll();
        return $data[0];
    }

    /**
     * 根据概率产生用户中奖奖品
     * @param $activeid int 抽奖活动id
     * @param $gameid       int 抽奖活动对应奖品表主键
     * @param $uid          int 用户id
     * @return array    返回选择的奖品
     */
    static public function getPrizesList($activeid, $gameid, $uid) {

        //概率对比值自动从1到10000中随机生成,然后去表中对比 获取中奖奖品

        $connection = \Yii::$app->db;
        $command = $connection->createCommand("select dp.title,dp.content,dpgp.gameprizesid,dpgp.gameid,dpgp.num,dpgp.prizesid,dpgp.probability_start,dpgp.probability_end,dp.type "
                . "from dk_prize_game_prizes  as dpgp "
                . "inner join dk_prizes as dp on dpgp.prizesid=dp.prizesid INNER JOIN dk_activity as da on da.gameid=dpgp.gameid  where dpgp.gameid=$gameid "
                . "and  dpgp.status=1 and dp.status=1  and  da.activityid={$activeid}"); #and num>0 
        $data = $command->queryAll();
        $array = [];
        $randArray = [];

        //判断总和不能都为num=0
        $count = '';
        foreach ($data as $k => $v) {
            $count+=$v['num'];
        }
        if (!$count) {
            return [];
        }

        $rand = rand(1, 10000);
        //1积分2虚拟3实物
        foreach ($data as $key => $val) {
            if ($val['probability_start'] <= $rand && $val['probability_end'] >= $rand && $val['num'] != 0) {
                //活得积分后直接加入到用户 eci_user_coin 表中
                if ($val['type'] == 1) {
                    $model = DkPrizeGamePrizes::findOne(['gameprizesid' => $val['gameprizesid']]);
                    $model->num = $model->num - 1;
                    $model->save();
                    $time = strtotime(date('Y-m-d', time()));
                    $timeEnd = strtotime(date('Y-m-d 23:59:59', time())); # 
                    $connection = \Yii::$app->db;
                    $command = $connection->createCommand("select recordid from dk_prize_share_record where activityid={$activeid} and uid={$uid} and ctime between $time and $timeEnd and status=1 order by recordid desc limit 1");
                    $recordData = $command->queryAll();
                    $recodeModel = DkPrizeShareRecord::findOne(['recordid' => $recordData[0]['recordid']]);
                    $recodeModel->status = 2;
                    if ($recodeModel->save(true)) {
                        UserCoinService::addCoinNew($uid, $val['content']);
                        $content = $val['content'];
                        $DkPrizeUser = new DkPrizeUser();
                        $DkPrizeUser->activityid = $activeid;
                        $DkPrizeUser->uid = $uid;
                        $DkPrizeUser->prizesid = $val['prizesid'];
                        $DkPrizeUser->ctime = time();
                        $DkPrizeUser->save();
                    }
                }
                $array = [
                    'gameprizesid' => $val['gameprizesid'],
                    'prizesid' => $val['prizesid'],
                    'content' => isset($content) ? $content : "0",
                    'activeid' => $activeid,
                    'type' => $val['type'],
                    'title' => $val['title']
                ];
            }
        }

        //如果抽中奖品中某个奖品为空,则获取数值最大的哪个奖品
        if (empty($array)) {
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
                'field' => 'num', //排序字段  
            );
            $arrSort = [];
            foreach ($data AS $uniqid => $row) {
                foreach ($row AS $key => $value) {
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            if ($sort['direction']) {
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
            }
            if ($data[0]['type'] == 1) {
                $model = DkPrizeGamePrizes::findOne(['gameprizesid' => $data[0]['gameprizesid']]);
                $model->num = $model->num - 1;
                $model->save();
                $time = strtotime(date('Y-m-d', time()));
                $timeEnd = strtotime(date('Y-m-d 23:59:59', time())); # 
                $connection = \Yii::$app->db;
                $command = $connection->createCommand("select recordid from dk_prize_share_record where activityid={$activeid} and uid={$uid} and ctime between $time and $timeEnd and status=1 order by recordid desc limit 1");
                $recordData = $command->queryAll();
                $recodeModel = DkPrizeShareRecord::findOne(['recordid' => $recordData[0]['recordid']]);
                $recodeModel->status = 2;
                if ($recodeModel->save(true)) {
                    UserCoinService::addCoinNew($uid, $data[0]['content']);
                    $content = $data[0]['content'];
                    $DkPrizeUser = new DkPrizeUser();
                    $DkPrizeUser->activityid = $activeid;
                    $DkPrizeUser->uid = $uid;
                    $DkPrizeUser->prizesid = $val['prizesid'];
                    $DkPrizeUser->ctime = time();
                    $DkPrizeUser->save();
                }
            }
            $array = [
                'gameprizesid' => $data[0]['gameprizesid'],
                'prizesid' => $data[0]['prizesid'],
                'content' => isset($data[0]['content']) ? $data[0]['content'] : "0",
                'activeid' => $activeid,
                'type' => $data[0]['type'],
                'title' => $data[0]['title']
            ];
        }
        return $array;
    }

    /**
     * 获取中奖用户提交的个人信息写入到中奖用户表
     * @param type $array
     * @return bool 写入用户信息
     */
    public static function setUserPrize($array) {
        $false = true;
        $model = new DkPrizeUser();
        //insert
        $model->activityid = $array['activityid'];
        $model->uid = $array['uid'];
        $model->prizesid = $array['prizesid'];
        $model->mobile = $array['mobile'];
        $model->address = $array['address'];
        $model->name = $array['name'];
        $model->ctime = time();
        if ($model->save(true)) {
            return $false;
        } else {
            $false = false;
            return $false;
        }
    }

    /**
     * 获取中奖用户提交的个人信息改变抽奖活动对应奖品表奖品的数量同事锁定分享记录
     * @param type $gameprizesid
     * @param type $activeid
     * @param type $uid
     * @return boolean
     */
    public static function setPrizeNum($gameprizesid, $activeid, $uid) {
        $false = false;
        $model = new DkPrizeGamePrizes();
        //查看奖品是否还有库存
        $model = DkPrizeGamePrizes::findOne(['gameprizesid' => $gameprizesid]);
        $time = strtotime(date('Y-m-d', time()));
        $timeEnd = strtotime(date('Y-m-d 23:59:59', time()));
        //查出分享表中要重置的状态
        $recodeModel = DkPrizeShareRecord::find()
                        ->select('*')
                        ->where(['activityid' => $activeid, 'status' => 1])
                        ->andWhere(['uid' => $uid])
                        ->andWhere(['>=', 'ctime', $time])
                        ->andWhere(['<=', 'ctime', $timeEnd])
                        ->Asarray()->all();
        if (isset($model->num) && $model->num > 0 && count($recodeModel) > 0) {
            $model->num = $model->num - 1;
            $recodeModel = DkPrizeShareRecord::findOne(['recordid' => $recodeModel[0]['recordid']]);
            $recodeModel->status = 2;
            if ($model->save() && $recodeModel->save()) {
                $false = true;
            }
        }
        return $false;
    }

}
