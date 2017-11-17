<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\TagGroup;
/**
 * 标签分类
 */
class TagGroupService extends TagGroup
{
    public static function getTagGrounpByPage($f_catalog_id,$s_catalog_id)
    {
        $query=parent::find();
        $query->from(parent::tableName())->where(['status'=>1]);
            if($f_catalog_id){
                $query->andWhere(['f_catalog_id'=>$f_catalog_id]);  
            }
            if($s_catalog_id){
                 $query->andWhere(['s_catalog_id'=>$s_catalog_id]);
            }
        $countQuery=$query->count();
        $pages=new Pagination(['totalCount'=>$countQuery]);

        $query=new \yii\db\Query();
        $query->select("*")->from(parent::tableName())->where(['status'=>1]);
                if($f_catalog_id){
                    $query->andWhere(['f_catalog_id'=>$f_catalog_id]);  
                }
                if($s_catalog_id){
                     $query->andWhere(['s_catalog_id'=>$s_catalog_id]);
                }
                //->where(['is_del' => 0])
        $models=$query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('taggroupid DESC')
                ->all();
        return ['models' => $models,'pages' => $pages,'pageSize'=>1]; 
    }
}
