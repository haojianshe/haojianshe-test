<?php

namespace console\service;

use Yii;
use common\models\myb\UserCoupon;

class UserCouponService extends UserCoupon {    
     /**
     * 用户获得课程卷后发推送消息
     * @param unknown $uid 如果是int型，则发给一个用户，如果是数组则代表群发
     * @param unknown $couponid
     * @param unknown $coupon_name
     */
    static function couponPushMsg($uid, $couponid,$coupon_name ) {
        $rediskey = 'offhubtask';
        $redis = Yii::$app->cachequeue;
    
        //推送类型
        $params['tasktype'] = 'couponmsg';
        //接收用户  
        if(is_array($uid)){
            $params['to_uid'] =  implode(",",$uid);
        }
        else {
            $params['to_uid']=$uid;
        }
        $params['couponid'] = $couponid;
        $params['coupon_name'] = $coupon_name;
        $params['tasktctime'] = time();
        $value = json_encode($params);
    
        $redis->lpush($rediskey, $value);
    }
}
