<?php
namespace api\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Startpage;
use common\redis\Cache;

/**
 * 启动页
 * @author Administrator
 *
 */
class StartpageService extends Startpage
{   
	/**
	 * 获取在有效期内启动页id
     */
    public static function getIds(){
    	$key = 'startpage_avliad_ids';
    	$redis = Yii::$app->cache;
    	
    	//从缓存获取
    	$ids = $redis->getValue($key);
    	if($ids){
    		$arrids = explode(',', $ids);
    	}
    	//从数据库获取
    	$curtime = time();
    	$arrids = (new \yii\db\Query())
    	->select(['pageid'])
    	->from(parent::tableName())
    	->where(['status'=>0])
    	->andWhere(['<','startdate',$curtime])
    	->andWhere(['>','expiredate',$curtime])
    	->limit(100)
    	->all();
    	$ids = '';
    	foreach ($arrids as $k=>$v){
    		if($ids != ''){
    			$ids .= ',';
    		}
    		$ids .= $v['pageid'];
    	}
    	$arrids = [];
    	if($ids!=''){
    		//存缓存，10分钟
    		$redis->setValue($key, $ids,600);
    		$arrids = explode(',', $ids);
    	}
    	return $arrids;    	
    }
    
    /**
     * 获取第一张启动图的id，不管是否过期
     * @return multitype:
     */
	public static function getFirstId(){
    	$key = 'startpage_first_id';
    	$redis = Yii::$app->cache;
    	
    	//从缓存获取
    	$id = $redis->getValue($key);
    	if($id){
    		return $id;
    	}
    	//从数据库获取
    	$curtime = time();
    	$id = (new \yii\db\Query())
    	->select(['pageid'])
    	->from(parent::tableName())
    	->where(['status'=>0])
    	->limit(1)
    	->one();
    	if($id){
    		return $id['pageid'];
    	}
    	return 0;    	
    }
    
    /**
     * 根据id获取实体信息
     * @param unknown $pageid
     * @return unknown
     */
	public static function getByPageid($pageid) {
		$redis = Yii::$app->cache;
		$rediskey = "startpage_detail_" .$pageid;
		
		$ret = $redis->hgetall($rediskey);
		if (!$ret) {
			//从数据库中获取
			$ret =  static::findOne(['pageid'=>$pageid])->attributes;
			if($ret){
				//存缓存,保留24*3小时
				$redis->hmset($rediskey, $ret);
				$redis->expire($rediskey,3600*24*3);
			}
		}
		return $ret;
	}
}