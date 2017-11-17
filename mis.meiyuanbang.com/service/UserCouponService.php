<?php

namespace mis\service;

use Yii;
use common\models\myb\UserCoupon;
use yii\data\Pagination;
use mis\service\UserService;
use mis\service\CouponService;
class UserCouponService extends UserCoupon {
     
    /**
     * 分页获取所有课程券
     */
    public static function getByPage($couponid) {
        $query = parent::find()->alias("a")->select("b.*,c.*,a.*")->where(["<>","a.status",3])->innerJoin(UserService::tableName().' b',"a.uid=b.uid")->innerJoin(CouponService::tableName().' c',"c.couponid=a.couponid");
        if($couponid){
              $query->andWhere(['a.couponid'=>$couponid]); 
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = $query
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('usercouponid DESC')
                ->asArray()
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
    /**
        增加用户课程券
    **/
    public static function addCoupon($uid,$couponid){
        $model=new UserCoupon();
        $model->uid=$uid;
        $model->couponid=$couponid;
        $model->coupongrantid=0;
        $model->status=1;
        $ret=$model->save();
        return $ret;
    }
    
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
