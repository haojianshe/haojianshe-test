<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\LessonSection;

/**
 * 黑名单相关逻辑
 */
class LessonSectionService extends LessonSection
{   
	//考点详情 lesson_detail_ +考点id
	private  $lesson_detail_rediskey = 'lesson_detail_';
	//考点 阶段lesson_detail_section_  +阶段id
	private  $lesson_section_rediskey = 'lesson_detail_section_';
	
	/**
	 * 获取考点的全部section
	 * @param unknown $lessonid
	 */
	public static function getBylessonId($lessonid){
		$ret = parent::find()->where(['lessonid'=>$lessonid])
		->orderBy('listorder,sectionid')
		->all();
		return $ret;
	}
	
	/**
	 * 获取一个lessonid下的最大的listorder
	 */
	public static function getMaxListorder($lessonid){
		$ret = parent::find()->where(['lessonid'=>$lessonid])
		->max('listorder');
		return $ret;
	}
	
	/**
	 * 重载model的save方法，保存后处理缓存
	 * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
	 */
	public function save($runValidation = true, $attributeNames = NULL){
		$isnew = $this->isNewRecord;
		$redis = Yii::$app->cache;
		 
		$ret = parent::save($runValidation,$attributeNames);
		//处理缓存
		if($isnew==false){
			//新建节点需要清理掉对应的lessonid的detail缓存
			$rediskey = $this->lesson_detail_rediskey.$this->lessonid;
			$redis->delete($rediskey);
		}
		else{
			//编辑节点时要清除节点的对应缓存
			$rediskey = $this->lesson_section_rediskey.$this->sectionid;
			$redis->delete($rediskey);
		}
		return $ret;
	}
}
