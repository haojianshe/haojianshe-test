<?php
namespace mis\service;

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
	 * 新红笔老师初始化范例图目录
	 * @param unknown $uid
	 * @return unknown
	 */
	static function initFolder($uid){
		//色彩
		static::initFolderByFolderName($uid, '色彩');
		//速写
		static::initFolderByFolderName($uid, '速写');
		//素描
		static::initFolderByFolderName($uid, '素描');
		//设计
		static::initFolderByFolderName($uid, '设计');		
	}
	
	static function initFolderByFolderName($uid,$folderName){
		$model = static::findOne(['teacher_uid'=>$uid,'foldername'=>$folderName]);

		if($model){
			return;
		}
		$model = new CorrectTeacherFolderService();
		$model->foldername = $folderName;
		$model->teacher_uid = $uid;
		$model->parent_folderid = 0;
		$model->pic_count = 0;
		$model->ctime = time();
		$model->save();
	}
}
