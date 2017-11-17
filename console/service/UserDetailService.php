<?php
namespace console\service;

use Yii;
use console\models\UserDetail;

/**
 * 用户详情相关逻辑
 */
class UserDetailService extends UserDetail
{        
    /**
     * 根据uid获取用户详情
     * 首先从缓存获取用户的数据，如果获取不到则从数据库获取，但是从数据库获取后，不改动缓存
     * @param unknown $uid
     */
    public static function getNameByUid($uid){
    	$redis = Yii::$app->cache;
    	$rediskey = "user_detail_" .$uid;
    	
    	$sname = $redis->hget($rediskey,'sname');
    	if($sname){
    		return $sname;
    	}
    	else{
    		//从数据库中获取
    		$model = static::findOne(['uid' => $uid]);
    		if($model){
    			return $model->sname; 
    		}
    	}
    	return null;
    }
}
