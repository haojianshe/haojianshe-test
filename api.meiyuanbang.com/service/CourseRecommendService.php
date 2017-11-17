<?php
namespace api\service;
use common\models\myb\CourseRecommend;
use Yii;
use common\redis\Cache;
/**
* 相关方法
*/
class CourseRecommendService extends CourseRecommend
{
 	public static function getCourseRecommendList($recommendid,$lastid=NULL,$rn=50){
 	    //$redis = Yii::$app->cache;
 	    //$rediskey="course_recommend".$recommendid;
 	    //$redis->delete($rediskey);
 	    //$list_arr=$redis->lrange($rediskey,0, -1);
 	    //不用缓存 外层已经缓存
 	    //if(empty($list_arr)){
 	    	$list_arr=[];
 	        $model=self::getCourseRecommendListDb($recommendid);
 	        $ids='';
 	        foreach ($model as $key => $value) {
 	            $ids.=$value['courseid'].',';
 	           // $ret = $redis->rpush($rediskey, $value['courseid'],true);
 	        }
 	       // $redis->expire($rediskey,3600*24*3);
 	        if($ids){
 	        	$ids=substr($ids, 0,strlen($ids)-1);
 	        	$list_arr=explode(',', $ids);
 	        }
 	        
 	    //}
 	    return $list_arr;
 	}
 	public static function getCourseRecommendListDb($recommendid){
 		return self::find()->select('courseid')->where(['recommendid'=>$recommendid])->orderBy("sort_id asc")->all();
 	}
}