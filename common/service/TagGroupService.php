<?php
namespace common\service;

use Yii;
use common\models\myb\TagGroup;
/**
 * 标签分类
 */
class TagGroupService extends TagGroup
{
	/**
	 * 根据一二级分类获取标签组
	 * @param  [type] $f_catalog_id [description]
	 * @param  [type] $s_catalog_id [description]
	 * @return [type]               [description]
	 */
	public static function getTagGroupByType($f_catalog_id,$s_catalog_id){
		return self::find()->where(['f_catalog_id'=>$f_catalog_id])->andWhere(['s_catalog_id'=>$s_catalog_id])->andWhere(['status'=>1])->asArray()->all();
	}
}
