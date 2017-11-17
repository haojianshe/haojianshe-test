<?php
namespace console\service;

use Yii;
use common\models\myb\GroupBuy;

/**
 * 团购推送
 */
class GroupBuyService extends GroupBuy
{   
    /**
        得到结束未发送通知的团购id
    **/
    public static function getEndGroupBuy(){
        $endgroupbuy=self::find()->select('groupbuyid,title')->where(['<',"end_time",time()])->andWhere(['status'=>1])->andWhere(['has_notice'=>0])->asArray()->all();
        return $endgroupbuy;
    }     
    /**
        更改团购通知状态为已通知
    **/
    public static function updateNoticeStatus($groupbuyid){
        $groupbuyinfo=self::find()->where(['groupbuyid'=>$groupbuyid])->one();
        $groupbuyinfo->has_notice=1;
        $groupbuyinfo->save();
    }
    /**
     * 用户获得课程卷后发推送消息
     * @param unknown $uid 如果是int型，则发给一个用户，如果是数组则代表群发
     * @param unknown $couponid
     * @param unknown $coupon_name
     */
    static function groupBuyPushMsg($uid,$groupbuy_title) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;
    
        //推送类型
        $params['tasktype'] = 'groupbuymsg';
        //接收用户  
        if(is_array($uid)){
            $params['to_uid'] =  implode(",",$uid);
        }
        else {
            $params['to_uid']=$uid;
        }
        $params['groupbuy_title'] = $groupbuy_title;
        $params['tasktctime'] = time();
        $value = json_encode($params);
        $redis->lpush($rediskey, $value);
    }
}
