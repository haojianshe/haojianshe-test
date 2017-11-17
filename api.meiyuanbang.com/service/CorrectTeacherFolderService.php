<?php
namespace api\service;

use Yii;
use common\models\myb\CorrectTeacherFolder;

/**
 * 
 * @author Administrator
 *
 */
class CorrectTeacherFolderService extends CorrectTeacherFolder 
{	
	/**
	 * 获取用户的所有目录
	 * @param unknown $uid
	 * @return unknown
	 */
	static function getAllFolder($uid){
		$query = new \yii\db\Query();
		$ret = $query->select('*')
		->from(parent::tableName())
		->where(['teacher_uid'=>$uid])
		->orderBy('folderid DESC')
		->all();
		return $ret;
	}
	
	/**
	 * 根据老师的目录
	 * @param unknown $uid
	 * @param unknown $foldername
	 */
	static function getFolderByName($uid,$foldername){
		$query = new \yii\db\Query();
		$ret = $query->select('*')
		->from(parent::tableName())
		->where(['teacher_uid'=>$uid])
		->andWhere(['foldername'=>$foldername])
		->one();
		return $ret;
	}
	
	/**
	 * 目录中增加范例图
	 * @param unknown $uid
	 * @param unknown $folderId
	 * @param unknown $addCount
	 */
	static function updatePicCount($folderId,$addCount){
		$model = static::findOne(['folderid' => $folderId]);
		if($model){
			$model->pic_count += $addCount;
			return $model->save();
		}
		return false;
	}
}
