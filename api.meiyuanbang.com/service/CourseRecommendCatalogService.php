<?php
namespace api\service;
use common\models\myb\CourseRecommendCatalog;
use Yii;
use common\redis\Cache;
use common\service\dict\CourseDictDataService;
use api\service\CourseRecommendService;
use api\service\CourseService;

/**
* 相关方法
*/
class CourseRecommendCatalogService extends CourseRecommendCatalog
{
 	/**
 	 * 获取详情
 	 */
 	public static function getCatalogInfo($uid=-1){
 	    $rediskey="course_catalog";
 	    $redis = Yii::$app->cache;
 	    //$redis->delete($rediskey);
 	    $detail=$redis->get($rediskey);
 	    if (empty($detail)) {
 	       $detail=self::find()->orderBy("sort_id asc")->asArray()->all();
 	       if($detail){
	 	       	foreach ($detail as $key => $value) {
	 	       		//处理分类
	 	       		$detail[$key]['f_catalog']=CourseDictDataService::getCourseMainTypeNameById($detail[$key]['f_catalog_id']);
	 	       		$detail[$key]['s_catalog']=CourseDictDataService::getCourseSubTypeById($detail[$key]['f_catalog_id'],$detail[$key]['s_catalog_id']);
	 	       		$courseids=CourseRecommendService::getCourseRecommendList($value['recommendid']);
	 	       		$detail[$key]['courseids']=$courseids;
	 	       		//$detail[$key]['course_list']=CourseService::getListDetail($courseids);
	 	       	}
 	       		$detail=json_encode($detail);
 	            $redis->set($rediskey,$detail);
 	            $redis->expire($rediskey,3600*24*3);
 	       }else{
 	       		$detail=json_encode($detail);
 	       }
 	    }
 	    //详情单独获取
 	    $catalog=json_decode($detail,true);
 	    if($catalog){
 	    	foreach ($catalog as $key => $value) {
 	       		$catalog[$key]['course_list']=CourseService::getListDetail($catalog[$key]['courseids'],$uid);
 	       	}
 	    }
 	    return $catalog;
 	}
}