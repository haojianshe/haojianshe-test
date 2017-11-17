<?php
namespace api\service;
use Yii;
use common\models\myb\UserCoupon;
use common\models\myb\Coupon;
use common\lib\myb\enumcommon\SysMsgTypeEnum;
/**
 * @author ihziluoh  用户优惠券
 */
class UserCouponService extends UserCoupon {
    /**
     * 得到优惠券列表
     * @param  [type] $uid         [description]
     * @param  [type] $status [description] 1=>未使用，2=>已使用，3=>已删除 4=>已过期
     * @param  [type] $lastid      [description]
     * @param  [type] $rn          [description]
     * @return [type]              [description]
     */
    public static function getList($uid,$status=NULL,$lastid=NULL,$rn=10){
        $query=self::find()->select("a.usercouponid")->alias("a")->where(['<>','a.status',3])->innerJoin(Coupon::tableName().' b',"a.couponid=b.couponid")->andWhere(['a.uid'=>$uid]);
        //未使用 已使用
        if($status==1 ||$status==2  ){
            $query->andWhere(['a.status'=>$status]);
            $query->andWhere("(b.etime >=".time()." and b.btime<=".time().")");
        }
        //已过期
        if($status==4){
            $query->andWhere("(b.etime <".time()." or b.btime>".time().")");
        }   
        if($lastid){
            $query->andWhere(['<','usercouponid',$lastid]);
        }
        $usercouponids=$query->orderBy('usercouponid desc')->limit($rn)->asArray()->all();
        $usercouponid_arr=[];
        if($usercouponids){
            foreach ($usercouponids as $key => $value) {
                 $usercouponid_arr[]= $value['usercouponid'];
            }
        }
        
        $ret_arr= self::getCouponListInfo($usercouponid_arr);
        return $ret_arr;
    }
    /**
     * 筛选能用的优惠卷
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getNotUsedList($uid){
        $query=self::find()->select("a.usercouponid")->alias("a")->where(['<>','a.status',3])->innerJoin(Coupon::tableName().' b',"a.couponid=b.couponid")->andWhere(['a.uid'=>$uid])->andWhere(['<','b.btime',time()])->andWhere(['>','b.etime',time()]);
        $query->andWhere(['a.status'=>1]);
        $usercouponids= $query->orderBy('usercouponid desc')->asArray()->all();
        $usercouponid_arr=[];
        foreach ($usercouponids as $key => $value) {
             $usercouponid_arr[]= $value['usercouponid'];
        }
        $ret_arr= self::getCouponListInfo($usercouponid_arr);
        return $ret_arr;
    }

    /**
     * 得到用户未使用优惠卷信息
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function getNoUserCouponInfo($uid,$usercouponid){
        return $query=self::find()->select("a.*,b.*")->alias("a")->where(['<>','a.status',3])->innerJoin(Coupon::tableName().' b',"a.couponid=b.couponid")->andWhere(['a.uid'=>$uid])->andWhere(['a.usercouponid'=>$usercouponid])->andWhere(['a.status'=>1])->andWhere(['<','b.btime',time()])->andWhere(['>','b.etime',time()])->asArray()->one();
    }

    public static function getCouponListInfo($usercouponids){
        $ret=[];
        if($usercouponids){
             foreach ($usercouponids as $key => $value) {
                $ret[]=self::getCouponDetail($value);
            }
        }
       
        return $ret;
    }
    /**
     * 获取优惠卷详情
     * @param  [type] $usercouponid [description]
     * @return [type]               [description]
     */
    public static function getCouponDetail($usercouponid){
        $couponinfo=self::find()->select("a.*,b.*")->alias("a")->innerJoin(Coupon::tableName().' b',"a.couponid=b.couponid")->andWhere(['a.usercouponid'=>$usercouponid])->asArray()->one();
        $couponinfo['expiring_tip']=self::getExpiringTip($couponinfo['etime']);
        return $couponinfo;
    }
    /**
     * 更新优惠卷状态改为已使用
     * @param  [type] $usercouponid [description]
     * @param  [type] $uid          [description]
     * @return [type]               [description]
     */
    public static function updateCouponStatus($usercouponid,$uid){
        $model=self::find()->where(['uid'=>$uid])->andWhere(['usercouponid'=>$usercouponid])->andWhere(['status'=>1])->one();
        if(!$model){
            return false;
            //die('优惠卷不存在');
        }
        $model->status=2;
        $ret=$model->save();
        if($ret){
            return true;
        }
        return false;
    }

    /**
     * 获取课程券即将过期提醒
     * @param  [type] $stime [时间戳]
     * @return [type]        [description]
     */
    public static function getExpiringTip($stime){
        $tip='';
        $diff_time=$stime-time();
        if($diff_time>0){
            $diff_day=intval($diff_time/24)+1;
            //7天之内的显示过期
            if($diff_day<=6){
                  $tip=$diff_day.'天后过期';
            }
        }
        return $tip;
    }
    
    /**
     * 获取新课程卷数量，用于小红点展示
     * 从缓存中读取数据,如果缓存失效则不用处理
     * @param unknown $uid
     * @return number|unknown
     */
    static function getNewCouponNum($uid) {
    	$redis = Yii::$app->cache;
		$redis_key = 'ms:couponmsg';
		
		$ret = $redis->zscore($redis_key, $uid);
		if(empty($ret)){
			return 0;
		}
		else{
			return $ret;
		} 
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
    
	/**
	 * 查看课程卷列表后清除小红点
	 * @param unknown $uid
	 * @param unknown $otheruid
	 */
	static function removeRed($uid){
		$redis = Yii::$app->cache;
		$redis_key = 'ms:couponmsg';
		$redis->zrem($redis_key,$uid);
	}
    /**
        增加课程券并且推送
    **/
    public static function addCouponPushMsg($uid,$couponid){
        $couponinfo=Coupon::find()->where(['couponid'=> $couponid])->asArray()->one();
        $ret=self::addCoupon($uid,$couponid);
        if($ret){
            self::couponPushMsg($uid, $couponid,$couponinfo['coupon_name']);
        }
        
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
}
