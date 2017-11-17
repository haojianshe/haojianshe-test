<?php
namespace api\service;

use Yii;
use common\models\myb\LessonSection;

/**
 * 考点_节点
 *
 */
class LessonSectionService extends LessonSection 
{
	/**
	 * 获取一个考点的所有节点
	 * @param unknown $lessonid
	 */
	static function getIdsByLessonid($lessonid){
		$ret = parent::find()
		->select('sectionid')
		->where(['lessonid'=>$lessonid])
		->orderBy('listorder')
		->all();
		return $ret;
	}
	
	/**
	 * 获取一条节点信息
	 * @param unknown $sectionid
	 * @return unknown|multitype:
	 */
	static function getById($sectionid){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_detail_section_'.$sectionid;
		
		$ret = $redis->get($redis_key);
		if($ret){
			return json_decode($ret,true);
		}
		//数据库获取
		$ret = static::findOne(['sectionid'=>$sectionid])->attributes;
		if($ret){
			$ret=json_encode($ret);
			//存缓存,保留24*3小时
			$redis->set($redis_key, $ret);
			$redis->expire($redis_key,3600*24*3);
		}
		return json_decode($ret,true); 
	}	
}
