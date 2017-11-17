<?php
namespace api\service;

use Yii;
use common\models\myb\LessonPic;
use common\service\CommonFuncService;

/**
 * 
 * @author Administrator
 *
 */
class LessonPicService extends LessonPic 
{
	/**
	 * 获取一个节点下的所有图片信息
	 */
	static function getBySectionId($sectionid){
		$redis = Yii::$app->cache;
		$redis_key = 'lesson_detail_section_pics_'.$sectionid;
		$ret = $redis->getValue($redis_key);
		if($ret){
			$ret= json_decode($ret,true);
		}else{
			//从数据库获取
			$ret = (new \yii\db\Query())
	    		->select('*')
	    		->from(parent::tableName())
				->where(['sectionid'=>$sectionid])
				->orderBy('listorder,picid')
				->all();
			if($ret){
				$redis->setValue($redis_key,json_encode($ret),3600*24*3);
			}
		}
		//处理图片 增加l图返回
		foreach ($ret as $key => $value) {
			$img['h']=$value['pich'];
			$img['w']=$value['picw'];
			$img['url']=$value['picurl'];
			$ret[$key]['l']=CommonFuncService::getPicByType($img,"l");
			$ret[$key]['s']=CommonFuncService::getPicByType($img,"s");
			$ret[$key]['t']=CommonFuncService::getPicByType($img,"t");
		}
		
		return $ret;
	}
}