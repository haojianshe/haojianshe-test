<?php
namespace api\service;

use Yii;
use common\models\myb\UserCoin;
use common\service\DictdataService;
/**
 * 
 * @author Administrator
 *
 */
class UserCoinService extends UserCoin 
{
	/**
	 * 获取用户金币和等级信息，支持缓存
	 * @param unknown $uid
	 * @return unknown|boolean|NULL
	 */
	static function getByUid($uid) {
		$redis = Yii::$app->cache;
		$rediskey = "user_coin_" .$uid;
		$ret = $redis->hgetall($rediskey);
		if($ret){
			return $ret;
		}
		//数据库获取
		$ret =  static::findOne(['uid'=>$uid])->attributes;
		if($ret){
			//存缓存,保留24*3小时
			$redis->hmset($rediskey, $ret);
			$redis->expire($rediskey,3600*24*3);
		}
		return $ret;
	}



    /**
     * 用户增加积分
     * @author ihziluoh
     * @param $$uid 用户id，$type加积分类型，$maxcount每天每种类型加积分，$coins增加积分数
     * @return  true
     */
    static  function addCoinsByUid($uid,$type,$maxcount,$coins){
        $count=self::getAddcoinTimes($uid,$type);
        $res=false;
        if ($count<$maxcount) {
            $res=self::addCoin($uid,$coins,$type); 
        }
        if($res){
            return $coins;
        }else{
              return 0;   
        }
    }
    /**
     * 给用户添加金币
     * @param unknown $uid
     * @param unknown $coin 要添加的金币数
     * @param string $checkgrade 是否检查用户等级，用户total金币达到升级标准后等级改变，null表示不检查
     * @param string $addtype 代表用户添加金币的来源，登录加金币 发帖加金币...，加过金币后在缓存中记录type加金币的次数，不记录次数
     * @return boolean
     */
    static function addCoin($uid, $coin,$addtype=null,$checkgrade=true) {
        //获取用户当前数据
        $model=static::findOne(['uid'=>$uid]);
        //$model =self::getByUid($uid);
        //未找到记录
        if(!$model){
            return false;
        }
        //计算总金币和剩余金币数
        $model->total_coin = $model['total_coin']+$coin;
        $model->remain_coin = $model['remain_coin']+$coin;
        //需要检查加完金币后用户是否升级
        if($checkgrade){
            $model->gradeid=self::getGradeByCoin($model->total_coin)['gradeid'];
        }
        //更新数据库
        $ret = $model->save();
        if(!$ret){
            return false;
        }
        //更新数据库成功后记录加金币次数
        if($addtype){
           self::handleTimesCache($uid, $addtype);
        }
        return true;
    }
    
    /**
     * 2.3.2以后加金币接口，老版本未动为保持兼容
     * @param unknown $uid
     * @param unknown $coin
     * @return boolean
     */
    static function addCoinNew($uid, $coin) {
    	//获取用户当前数据
    	$model=static::findOne(['uid'=>$uid]);
    	//未找到记录
    	if(!$model){
    		return false;
    	}
    	//计算总金币和剩余金币数
    	if($coin>0){
    		$model->total_coin = $model['total_coin']+$coin;
    	}    	
    	$model->remain_coin = $model['remain_coin']+$coin;
    	//更新数据库
    	$ret = $model->save();
    	if(!$ret){
    		return false;
    	}
    	return true;
    }
    
    /**
     * 用户加金币后，处理记录当天加金币次数的缓存
     * @param unknown $uid
     * @param unknown $addtype
     */
    static function handleTimesCache($uid,$addtype){
        $redis = Yii::$app->cache;
        //当前日期
        $date = date("Y.m.d");
        //首先从缓存获取
        $redis_key = 'user_coin_addtime_'.$uid;
        $redis_ret = $redis->hgetall($redis_key);
        if ($redis_ret) {
            //缓存中已有记录,需要判断日期是否已经改变
            if($redis_ret['date']!=$date){
                //更新缓存内容比较麻烦，目前先直接清掉
                $redis->delete($redis_key);
                $data['date'] = $date;
                $data['type' . $addtype] = 1;
            }
            else{
                $data['type' . $addtype] = $redis_ret['type' . $addtype]+1;
            }
        }
        else{
            //记录未在缓存中
            $data['date'] = $date;
            $data['type' . $addtype] = 1;
        }
        //更新缓存
        $retredis = $redis->hmset($redis_key, $data);
        //因为目前只记录1天内的情况，缓存失效时间暂定24小时零1分钟
        $retredis = $redis->expire($redis_key, 24*3600+60);
    }
    
    /**
     * 获取用户当天加金币的次数
     * @param unknown $uid
     * @param unknown $addtype 加金币的类型
     * @return boolean|number
     */
    static function getAddcoinTimes($uid,$addtype) {
        $redis = Yii::$app->cache;
        //当前日期
        $date = date("Y.m.d");
        //首先从缓存获取
        $redis_key = 'user_coin_addtime_'.$uid;
        $redis_ret = $redis->hgetall($redis_key,array('date', 'type'.$addtype));
        if ($redis_ret) {
            //判断日期是否为当天
            if($redis_ret['date']!=$date){
                return 0;
            }
            else{
                return $redis_ret['type'.$addtype];
            }
        }    
        return 0;
    }

    /**
     * 根据用户的金币总数或得对应级别信息
     * @param unknown $coin
     */
    static function getGradeByCoin($coin){
        //检查参数
        if(!is_numeric($coin)||$coin<0){
            return false;
        }
        $usergrade=DictdataService::getUserGrade();
        //获取级别
        foreach($usergrade as $k=>$v){
            if($v['scoin']<=$coin && $coin<=$v['ecoin']){
                return $v;
            }
        }
        //用户积分超过目前上限,返回最高级别
        return $usergrade[count($usergrade)-1];
    }


	 /**
     * 保存时操作缓存
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL){  
        $user_coin = 'user_coin_';
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;         
        $ret = parent::save($runValidation,$attributeNames);
        if($isnew==false){
            $redis_key_info = $user_coin . $this->uid;
            $redis->delete($redis_key_info);
        }else{

        }
        return $ret;
    }
}
