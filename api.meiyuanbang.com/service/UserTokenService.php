<?php
namespace api\service;

use Yii;
use common\models\myb\UserToken;

/**
 * 
 * @author Administrator
 *
 */
class UserTokenService extends UserToken 
{
	/**
	 * 根据token获取model
	 * @param unknown $token
	 */
	public static function getByToken($token){
		$redis = Yii::$app->cache;
		$rediskey = "usertoken_" .$token;
		
		$ret = $redis->hgetall($rediskey);
		if ($ret) {
			return $ret;
		}
		//从数据库中获取
		$query = new \yii\db\Query();
		$ret = $query->select('*')
		->from(parent::tableName())
		->where(['hash_key'=>$token])
		->andWhere(['is_valid'=>1])
		->limit(1)
		->one();
		if($ret){
			//存缓存,保留24小时
			$redis->hmset($rediskey, $ret);
			$redis->expire($rediskey,3600*24);
		}
		return $ret;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \yii\db\ActiveRecord::delete()
	 */
	public function delete(){
		$redis = Yii::$app->cache;
		
		$ret = parent::delete();
		//删除图片后清除掉section对应pic列表缓存
		$rediskey = "usertoken_" . $this->hash_key;
		$redis->delete($rediskey);
		return $ret;
	}
	/**
     * 生成返回token
     * @param  [type] $uid [description]
     * @param  string $ip  [description]
     * @return [type]      [description]
     */
    static function createToken($uid, $ip = '') {
        //生成token
        $create_time = time();
        $invalid_time = $create_time + 3600*24*365*5; // A week: 1week * 7day * 24hour * 60minute * 60second=604800
        $hash_str = strval($create_time).'-'.strval($uid).'-'.strval($ip).'-'.strval(rand());
        $hash_key = hash('md5', $hash_str);
        $model= new UserTokenService();
        $model->uid=$uid;
        $model->hash_key=$hash_key;
        $model->create_time =$create_time;
        $model->invalid_time=$invalid_time;
        $model->ip=$ip;
        $model->is_valid= 1;
        $model->save();
        return $hash_key;
    }
    
    /**
     * 重构save方法
     * add by 3.1.1,用于一个账号禁止在多台设备登录功能
     * @param unknown $token
     * @param unknown $invalidtime
     */
	public function save($runValidation = true, $attributeNames = NULL){
        $redis = Yii::$app->cache;
        $rediskey = "usertoken_" .$this->hash_key;
        
        $ret = parent::save($runValidation,$attributeNames);
        //清除缓存
        $redis->delete($rediskey);
        return $ret;
    }
}
