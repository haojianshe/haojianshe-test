<?php
namespace api\service;

use Yii;
use common\models\myb\Activity;
use common\models\myb\News;
use common\models\myb\NewsData;
use api\service\NewsService;

/**
 * 
 * @author 
 * 
 * 活动
 */
class ActivityService extends Activity 
{
	/**
	 * 获取分页的活动id列表数据
	 * @param unknown $lastid
	 * @param unknown $limit
	 * @return unknown
	 */	
	static function getIdsByPage($lastid,$limit){
		
		$redis = Yii::$app->cache;
		$redis_key = 'activity_newsids'; 
		
		//从缓存获取
		if($lastid == 0){
			$min = '-inf';
		}
		else {
			//+1排除当前id，否则会重复
			$min = ($lastid*-1)+1;
		}		
		//从缓存中获取
		$ret = $redis->zrangebyscore($redis_key,$min,'+inf',[0,$limit]);
		if(!$ret){
			//未取到数据则先建立缓存后重新获取
			static::buildListCache();
			$ret = $redis->zrangebyscore($redis_key,$min,'+inf',[0,$limit]);
		}
		return $ret;
	}
	
	/**
	 * 建立缓存
	 */	
	private static function buildListCache(){
		$redis = Yii::$app->cache;
		$redis_key = 'activity_newsids'; //活动缓存列表
		$cachesize = 1500;
	
		//建立缓存
		$query = new \yii\db\Query();
		$ids = $query->select(['newsid'])
		->from(parent::tableName())
		->where(['status'=>0])
		->andWhere(['activity_type'=>1])
		->orderBy('newsid DESC')
		->limit($cachesize)
		->all();
		if($ids){
			foreach ($ids as $k=>$v){				
				$redis->zadd($redis_key,$v['newsid']*-1,$v['newsid']);
			}
			//缓存3天
			$redis->expire($redis_key, 3600*24);
		}
	}
	
	/**
	 * 获取活动详情
	 * @param unknown $newsid
	 * @return multitype:unknown
	 */
    public static function getDetailById($newsid){
    	$redis = Yii::$app->cache;
    	$redis_key = 'activity_detail_' . $newsid; //活动详情缓存
    	
    	//缓存获取
    	$ret = $redis->hgetall($redis_key);
    	if($ret){
    		$ret['img'] = json_decode($ret['img'],true);
    		return $ret;
    	}
    	//数据库获取
    	$model=parent::findOne(["newsid"=>$newsid]);
    	if(!$model){
    		return null;
    	}
    	$model= $model->attributes;
    	$newsmodel = NewsService::getActivityInfo($newsid);
    	$ret = array_merge($newsmodel,$model);
    	//url地址
    	if($ret['activity_url']){
    		$ret['url'] = $ret['activity_url'];
    	}
    	else{
    		$ret['url'] = Yii::$app->params['sharehost'].'/activity?id='.$newsid;
    	}
    	$ret['img'] = json_encode($ret['img']);
    	$redis->hmset($redis_key,$ret);
    	$redis->expire($redis_key, 3600*24);
    	
    	$ret['img'] = json_decode($ret['img'],true);
    	return $ret;
    }
}
