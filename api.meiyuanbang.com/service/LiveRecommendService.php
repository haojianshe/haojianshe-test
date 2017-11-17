<?php
namespace api\service;
use common\models\myb\LiveRecommend;
use Yii;
use common\redis\Cache;
/**
* 相关方法
*/
class LiveRecommendService extends LiveRecommend
{
 	public static function getLiveRecommendIds(){
 	    $redis = Yii::$app->cache;
 	    $rediskey="live_recommend";
 	    //$redis->delete($rediskey);
 	    $list_arr=$redis->lrange($rediskey,0, -1);
 	    //判断缓存是否有内容 若无则重新建立缓存
 	    if(empty($list_arr)){
 	        $model=self::getLiveRecommendIdsDb();
 	        $ids='';
 	        foreach ($model as $key => $value) {
 	            $ids.=$value['liveid'].',';
 	            $ret = $redis->rpush($rediskey, $value['liveid'],true);
 	        }
 	        $redis->expire($rediskey,3600*24*3);
 	        if($ids){
 	        	$ids=substr($ids, 0,strlen($ids)-1);
 	        	$list_arr=explode(',', $ids);
 	        }else{
 	        	$list_arr=[];
 	        }
 	    }
 	    return $list_arr;
 	}
 	public static function getLiveRecommendIdsDb(){

 		return self::find()->select('liveid')->orderBy("sort_id asc")->all();
 	}
}