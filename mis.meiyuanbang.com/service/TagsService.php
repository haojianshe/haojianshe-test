<?php
namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Tags;
/**
 * 标签管理
 */
class TagsService extends Tags
{

    public static function getTagsByPage($taggroupid)
    {
        $query=parent::find();
        $countQuery=$query->from(parent::tableName())->where(['status'=>1])->andWhere(['taggroupid'=>$taggroupid])->count();
        $pages=new Pagination(['totalCount'=>$countQuery]);
        $query=new \yii\db\Query();
        $models=$query->select("*")->from(parent::tableName())->where(['status'=>1])
                ->andWhere(['taggroupid'=>$taggroupid])
                 ->offset($pages->offset)
                 ->limit($pages->limit)
                 ->orderBy('tagid DESC')
                 ->all();
        return ['models' => $models,'pages' => $pages,'pageSize'=>1]; 
    }
}
