<?php

namespace api\service;

use Yii;
use common\models\myb\CorrectReward;
use common\service\dict\CorrectGiftService;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
use api\service\UserDetailService;

/**
 * 获取打赏
 */
class CorrectRewardService extends CorrectReward {

    /**
     * @describe 获取老师收到的打赏奖品列表
     * @param  [int] $teacherid  老师id
     * @param  [int] $limit      取出条数
     * @param  [int] $lastid  分页id
     * @return array  $ret
     */
    static public function getTeacherRewardList($teacherid, $limit, $lastid) {
        //userinfo,ordertitle,bounty_fee,ctime
        $query = new \yii\db\Query();
        $query = $query->select('a.rewardid teacherbountyid,a.uid,a.teacheruid,a.gift_id,a.gift_price bounty_fee,a.ctime,b.sname teacher_name')
                ->from(parent::tableName() . ' as a ')
                ->innerJoin('ci_user_detail b', "a.teacheruid = b.uid")
                ->where(['a.teacheruid' => $teacherid, 'a.status' => 1]);
        //第一页时不需要lastid条件
        if ($lastid != 0) {
            $query = $query->andWhere(['<', 'rewardid', $lastid]);
        }
        $ret = $query->orderBy('rewardid desc')->limit($limit)->all();
        if ($ret) {
            $giftList = CorrectGiftService::getGiftData();
            foreach ($ret as $key => &$val) {
                foreach ($giftList as $k => $v) {
                    if ($v['gift_id'] == $val['gift_id']) {
                        $val['ordertitle'] = $v['gift_name'];
                        $val['bounty_fee'] = $val['bounty_fee'].'.00';
                        $val['username'] = UserDetailService::getUserName($val['uid'])['sname'];
                    }
                }
            }
        }
        
        return $ret;
    }

    /**
     * @describe 打赏表写入
     * @param type $gift_id     奖品id
     * @param type $pic         奖品价格
     * @param type $uid         用户id
     * @param type $teacheruid  老师id
     */
    static public function setPewardInfo($gift_id, $pic, $gift_name, $uid, $teacheruid) {
        $state = false;
        if (intval($gift_id) > 0) {
            $model = new CorrectReward();
            $model->uid = $uid;
            $model->teacheruid = $teacheruid;
            $model->gift_id = $gift_id;
            $model->gift_price = $pic;
            $model->gift_name = $gift_name;
            $model->ctime = time();
            if ($model->save()) {
                $state = $model->attributes['rewardid'];
            }
        }
        return $state;
    }

    /** 
     * @desc 处理打赏逻辑
     * @param int $rewardid 礼物表id
     */
    static public function updateTeacherReward($rewardid) {
        //修改老师礼物表状态
        $correctRewardModel = CorrectRewardService::findOne(['rewardid' => $rewardid]);
        $correctRewardModel->status = 1;
        if ($correctRewardModel->save()) {
            //发送通知 告诉老师,有人送礼物
            self::pushGiftMessage($correctRewardModel->uid, $correctRewardModel->teacheruid, $rewardid,$correctRewardModel->gift_name);
        }
    }

    /**
     * 打赏处理完成后发送推送消息
     * @param int $uid 发信人 
     * @param int $otheruid  收信人
     * @param int $mid 消息id
     */
    static public function pushGiftMessage($uid, $otheruid, $mid,$title) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;
        $params['tasktype'] = 'sysmsg';
        $params['tasktctime'] = time();
        $params['from_uid'] = $uid;
        $params['action_type'] = SysMsgTypeEnum::CORRECT_TEACHER_GIFT;
        $params['to_uid'] = $otheruid;
        $params['content_id'] = $mid;
        $params['content_name'] = $title;
        $value = json_encode($params);
        $redis->lpush($rediskey, $value);
    }

    /**
     * @desc 获取单条打赏信息记录
     * @param int $rewardid 打赏表id
     */
    static public function getCorrectRewardGiftInfo($rewardid = '') {
        if ($rewardid) {
            $ret = self::find()->select('gift_id')->where(['rewardid' => $rewardid])->asArray()->one();
            return CorrectGiftService::getGiftOneList($ret['gift_id']);
        }
    }
    
    /**
     * @desc 获取用户送礼物的订单列表
     * @param type $rewardid 打赏列表id
     */
    static public function getUserRewardOrderList($rewardid){
        $array = [];
       $rewardinfo =self::findOne(['rewardid'=>$rewardid]);
       //获取打赏列表信息
       foreach(CorrectGiftService::getGiftData() as $key=>$val){
           if($rewardinfo->gift_id==$val['gift_id']){
               $array['thumb_url'] = $val['gift_img'];
           }
       }
       return $array;
    }
    
    /**
     * @desc 获取用户打赏总金额
     * @teacheruid int 老师id
     */
    static public function getTotalReward($teacheruid,$stime=NULL,$etime=NULL){
         $query=self::find()->select("sum(gift_price) as bounty")->where(['teacheruid'=>$teacheruid,'status'=>1]);
        if($stime){
            $query->andWhere([">","ctime",$stime]);
        }
        if($etime){
            $query->andWhere(["<","ctime",$etime]);
        }
        return $query->asArray()->one()['bounty'];
    }

}
