<?php
namespace mis\service;

use Yii;
use mis\models\UserToken;
/**
 * 用户token
 */
class UserTokenService extends UserToken
{   
	/**
	 * 根据uid获取最新的一条token
	 * @param unknown $uid
	 * @return mixed|unknown
	 */
	public static function getByUid($uid){
		//从数据库中获取
		$ret = (new \yii\db\Query())
		->select('*')
		->from(parent::tableName())
		->where(['uid'=>$uid])
		->andWhere(['is_valid'=>1])
		->limit(1)
		->orderBy('invalid_time DESC')
		->one();
		return $ret;
	}
}
