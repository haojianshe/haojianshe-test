<?php
namespace mis\service;

use Yii;
use mis\models\LessonPic;

/**
 * 黑名单相关逻辑
 */
class LessonPicService extends LessonPic
{    
	//考点  阶段 所有图片存json   lesson_detail_section_pics_+阶段id
	private  $lesson_section_pics_rediskey = 'lesson_detail_section_pics_';
	
    /**
	 * 获取节点的全部pic,按照listorder排序
	 * @param unknown $lessonid
	 */
	public static function getBySectionid($sectionid){
		$ret = parent::find()->where(['sectionid'=>$sectionid])
		->orderBy('listorder,picid')
		->all();
		return $ret;
	}
	
	/**
	 * 获取某一个节点的图片数
	 * @param unknown $sectionid
	 * @return unknown
	 */
	public static function getPicCount($sectionid){
		$ret = parent::find()->where(['sectionid'=>$sectionid])
		->count();
		return $ret;
	}
	
	/**
	 * 获取一个sectionid下图片的最大的listorder
	 */
	public static function getMaxListorder($sectionid){
		$ret = parent::find()->where(['sectionid'=>$sectionid])
		->max('listorder');
		return $ret;
	}
	
	/**
	 * 重载model的save方法，保存后处理缓存
	 * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
	 */
	public function save($runValidation = true, $attributeNames = NULL){
		$redis = Yii::$app->cache;			
		$ret = parent::save($runValidation,$attributeNames);
		//处理缓存
		//新增图片清除掉section对应pic列表缓存
		$rediskey = $this->lesson_section_pics_rediskey.$this->sectionid;
		$redis->delete($rediskey);
		return $ret;
	}
	
	/**
	 * 重载delete，更新缓存
	 * (non-PHPdoc)
	 * @see \yii\db\ActiveRecord::delete()
	 */
	public function delete(){
		$redis = Yii::$app->cache;			
		$ret = parent::delete();
		//删除图片后清除掉section对应pic列表缓存
		$rediskey = $this->lesson_section_pics_rediskey.$this->sectionid;
		$redis->delete($rediskey);
		return $ret;
	}
}
