<?php
namespace console\service;

use Yii;
use console\models\UserPush;

/**
 * 用户详情相关逻辑
 */
class UserPushService extends UserPush
{        
    /**
     * 根据uid获取用户最后一个设备的token
     */
    public static function getByUid($uid){
    	$redis = Yii::$app->cache;
    	$rediskey = "user_push_" .$uid;
    	
    	$ret = $redis->getValue($rediskey);
    	if ($ret) {
    		$ret = json_decode($ret,true);
    		return $ret;
    	}
    	//从数据库中获取
    	$ret = (new \yii\db\Query())
    	->select('*')
    	->from(parent::tableName())
    	->where(['uid'=>$uid])
    	->limit(1)
    	->orderBy('id DESC')
    	->one();
    	
    	if($ret){
    		//存缓存,保留24小时
    		$redis->setValue($rediskey, json_encode($ret),3600*24);
    	}
    	return $ret;
    }
    
    /**
     * 批量获取andriod或者ios的token
     * @param unknown $uids 用户id数组
     * @param unknown $type 设备类型 1:android, 2:ios
     */
    static function getByDevicetype($uids,$type){
    	$ret = (new \yii\db\Query())
    	->select('*')
    	->from(parent::tableName())
    	->where(['device_type' => $type])
    	->andWhere(['in', 'uid', $uids])
    	->all();
    	return $ret;
    }
}
