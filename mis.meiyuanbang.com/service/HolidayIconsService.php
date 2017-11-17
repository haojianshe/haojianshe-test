<?php
namespace mis\service;

use Yii;
use common\models\myb\HolidayIcons;
use yii\data\Pagination;

class HolidayIconsService extends HolidayIcons
{
    public static function getDataByPage()
    {
        $query=parent::find();
        $query->from(parent::tableName())->where(['<>','status',2]);
           /* 
            if($condition){
                $query->andWhere(['condition'=>$condition]);  
            }
           */
        $countQuery=$query->count();
        $pages=new Pagination(['totalCount'=>$countQuery]);

        $query=new \yii\db\Query();
        $query->select("*")->from(parent::tableName())->where(['<>','status',2]);
                /*
                    if($condition){
                        $query->andWhere(['condition'=>$condition]);  
                    }
                */
        $models=$query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('iconsid DESC')
                ->all();
        return ['models' => $models,'pages' => $pages,'pageSize'=>1]; 
    }
   
}
