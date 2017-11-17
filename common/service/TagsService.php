<?php
namespace common\service;

use Yii;
use common\models\myb\Tags;
/**
 * 标签管理
 */
class TagsService extends Tags
{
	/**
	 * 通过标签分组获取标签数组
	 * @param  [type] $tagroupid [description]
	 * @return [type]            [description]
	 */
	public static function getTagsByGroupId($tagroupid){
		$tags= self::find()->select('tag_name')->where(['taggroupid'=>$tagroupid])->andWhere(['status'=>1])->asArray()->all();
		$return_tags=[];
		if($tags){
			foreach ($tags as $key => $value) {
				$return_tags[]=$value['tag_name'];
			}
		}
		return $return_tags;
	}
}
