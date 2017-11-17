<?php
namespace common\service\myb;

use Yii;
use common\models\myb\InfocollectionUser;

/**
 * 
 * @author Administrator
 */
class InfocollectionUserService extends InfocollectionUser
{
	/**
	 * 获取所有需要统计访问的用户列表
	 * @return unknown
	 */
	static function getCollectusers() {
		$rediskey = "infocollection_user_all";
		$redis = Yii::$app->cache;
		
		//获取用户id列表
		$userids=$redis->get($rediskey);
		if (empty($userids)) {
			$query=new \yii\db\Query();
			$ret = $query->select('*')->from(parent::tableName())->where(['status'=>0])->all();	
			if($ret){
				$userids='';
				foreach ($ret as $k=>$v){
					$userids .= $v['uid'] . ',';
				}
				$userids = trim($userids,',');
				//存缓存,5分钟重新获取一次
				$redis->set($rediskey,$userids);
				$redis->expire($rediskey,300);
			}			
		}
		//返回数组
		if($userids){
			$ret = explode(',', $userids);
			return $ret; 
		}
		//没有数据返回空
		return null;
	}

}
