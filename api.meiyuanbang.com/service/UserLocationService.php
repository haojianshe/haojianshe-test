<?php
namespace api\service;

use Yii;
use common\models\myb\UserLocation;
use common\redis\Cache;

/**
 * 
 * @author Administrator
 * 
 *
 */
class UserLocationService extends UserLocation 
{
	static $redis_key_pre = "redis_user_location_";
    /**
     * 根据uid获取记录
     * @param unknown $uid
     * @param string $fields
     * @return boolean|NULL|unknown
     */
    static function getInfoByUid($uid, $fields = '*') {
    	$redis = Yii::$app->cache;
    	//首先从缓存获取
    	$redis_key = self::$redis_key_pre . $uid;
         $redis->delete($redis_key);
        $redis_ret = $redis->hgetall($redis_key);
        if ($redis_ret) {
        	return $redis_ret;
        }
        //缓存中没有取到则从数据库获取
        $result_obj=UserLocation::findOne(['uid'=>$uid]);
        if ($result_obj) {
        	$result=$result_obj->attributes;
        }else{
            $result=array();
        }
        //未找到记录，记录一条空数据缓存，避免每次都读库
        if (0 >= count($result)) {
        	$retdata['uid'] = $uid;
        	$retdata['lon'] = 0;
        	$retdata['lat'] = 0;
        }
        else{
        	$retdata = $result;
        }
        //找到记录以后先写缓存,缓存时间3小时
        $retredis = $redis->hmset($redis_key, $retdata);
        $retredis = $redis->expire($redis_key, 3600*3);
        return $retdata;
    }
}
