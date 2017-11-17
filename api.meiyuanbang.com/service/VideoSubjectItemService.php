<?php

namespace api\service;

use Yii;

use common\models\myb\VideoSubjectItem;

/**
 * 
 * @author ihziluoh
 * 
 * 视频专题
 */
class VideoSubjectItemService extends VideoSubjectItem{
	
	/**
	 * 根据课程专题id获取课程id列表
	 * @param  [type] $subjectid [description]
	 * @return [type]            [description]
	 */
	public static function  getCourseIdsDb($subjectid){
		$model=self::find()->select('courseid')->where(['subjectid'=>$subjectid])->orderBy("listorder desc")->all();
		$ids_arr=[];
        foreach ($model as $key => $value) {
            $ids_arr[]=$value['courseid'];
        }
        $ids=implode(",", $ids_arr);
        return $ids;
	}
}
