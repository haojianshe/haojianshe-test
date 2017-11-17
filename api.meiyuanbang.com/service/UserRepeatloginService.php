<?php
namespace api\service;

use Yii;
use common\models\myb\UserRepeatlogin;

/**
 * 
 * @author Administrator
 *
 */
class UserRepeatloginService extends UserRepeatlogin 
{
	/**
	 * 获取用户上一次登录时的token
	 * 先从缓存获取，如果缓存中不存在则从数据库中获取最后一周内有效登录的token
	 */
	static function getUserLastToken($uid){
		$redis = Yii::$app->cache;
		$rediskey = "userLasttimeToken_" .$uid;
		
		$ret = $redis->hgetall($rediskey);
		
		if($ret){
			return $ret;
		}		
		//没有缓存，从数据库取最近的一条token有效记录
		$query = new \yii\db\Query();
		$model = $query->select('*')
		->from(parent::tableName())
		->where(['uid'=>$uid])
		->andWhere(['repeattime'=>0])
		->orderBy('lastlogintime desc')
		->one();
		if(!$model){
			return null;
		}
		//把用户最后一个token存缓存
		$ret = self::saveCurTokenToCache($uid, $model['token'], $model['lastlogintime']);
		return $ret;
		
	}
	
	/**
	 * 更新用户对应token的最后登录时间
	 * @param unknown $uid
	 */
	static function updateLastLoginTime($uid,$token){
		$connection = Yii::$app->db;
		$curtime = time();
		
		$strsql = "update myb_user_repeatlogin set lastlogintime=$curtime,updatetimes=updatetimes+1 where uid=$uid and token = '$token'";				
		$command = $connection->createCommand($strsql);
		$data = $command->execute();
		return $data;
	}
	
	/**
	 * 记录用户上一个token为失效状态
	 * @param unknown $uid
	 * @param unknown $token
	 * @return unknown
	 */
	static function updateRepeatTime($uid,$token){
		$connection = Yii::$app->db;
		$curtime = time();		
		
		$strsql = "update myb_user_repeatlogin set repeattime=$curtime where uid=$uid and token = '$token'";
		$command = $connection->createCommand($strsql);
		$data = $command->execute();
		return $data;
	}
	
	/**
	 * 把用户当前的token存到缓存中
	 * @param unknown $uid
	 * @param unknown $token
	 * @param unknown $lastLoginTime
	 */
	static function saveCurTokenToCache($uid,$token,$lastLoginTime){
		$redis = Yii::$app->cache;
		$rediskey = "userLasttimeToken_" .$uid;
		
		$ret['uid'] = $uid;
		$ret['token'] = $token;
		$ret['logintime'] = $lastLoginTime;
		//存缓存,保留24小时
		$redis->hmset($rediskey, $ret);
		$redis->expire($rediskey,3600*24);
		return $ret;
	}
	
	/**
	 * 添加一条token
	 * 使用sql语句，加入排重条件，避免并发情况下重复写入
	 * @param unknown $uid
	 * @param unknown $currentToken
	 * @param unknown $devicetype
	 */
	static function insertBySql($uid,$currentToken,$devicetype){
		$connection = Yii::$app->db;
		$curtime = time();
		
		$strsql = "insert into `myb_user_repeatlogin`(`uid`,`token`,`devicetype`,`lastlogintime`,`ctime`)select * from (select $uid,'$currentToken','$devicetype',$curtime as 'ltime',$curtime as 'ctime') t where NOT EXISTS(SELECT  `uid` FROM `myb_user_repeatlogin` WHERE `uid`=$uid and `token`='$currentToken') limit 1";
		$command = $connection->createCommand($strsql);
		$data = $command->execute();
		return $data;
	}
	
	/**
	 * 根据token获取需要提醒重复登录的记录
	 * 条件是，1天之内因重复被失效，并且没提醒过的记录
	 * @param unknown $token
	 */
	static function getNeedPromptToken($token){
		$ret = static::find()
				->where(['token'=>$token])
				->andWhere(['isprompt'=>0])
				->andWhere(['>','repeattime',0])
				->andWhere(['>','lastlogintime',time()-3600*24])
				->one();
		return $ret;
	}
}
