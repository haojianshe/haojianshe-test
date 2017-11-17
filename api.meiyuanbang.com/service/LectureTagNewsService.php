<?php

namespace api\service;

use Yii;
use common\models\myb\LectureTagNews;
use api\service\lectureService;
/**
 * 
 * @author ihziluoh
 * 
 * 精讲专题标签对应的文章列表
 */
class LectureTagNewsService extends LectureTagNews {
	/**
	 * 通过专题标签id 获取文章列表
	 * @param  [type] $tagid [description]
	 * @return [type]        [description]
	 */
	public static function getLectureTagNewsByTagid($tagid){
		$list=self::find()->where(['status'=>1])->andWhere(['lecture_tagid'=>$tagid])->orderBy("listorder desc")->asArray()->all();
		foreach ($list as $key => $value) {
			$lecture_info=lectureService::getLectureInfo($value['newsid']);
			$list[$key]=array_merge($lecture_info,$value);
		}
		return $list;
	}
}
