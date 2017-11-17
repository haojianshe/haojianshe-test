<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioEnroll;
use yii\data\Pagination;
//use common\service\dict\CourseDictDataService;

class StudioEnrollService extends StudioEnroll {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($classtypeid,$uid){

        $query = (new \yii\db\Query())
                ->select("a.*")
                ->innerJoin('myb_studio as b', 'b.uid=a.uid')
                ->from(parent::tableName() . ' as a')
                ->where(['<>', 'b.status', 2])
                ->andWhere(['a.status'=>1])
                ->andWhere(['a.classtypeid'=>$classtypeid])
                ->andWhere(['a.uid'=>$uid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("a.*")
                ->innerJoin('myb_studio as b', 'b.uid=a.uid')
                ->from(parent::tableName() . ' as a')
                ->where(['<>', 'b.status', 2])
                ->andWhere(['a.status'=>1])
                ->andWhere(['a.classtypeid'=>$classtypeid])
                ->andWhere(['a.uid'=>$uid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.listorder asc')#->createCommand()->getRawSql();
                ->all();
        return ['models' => $rows, 'pages' => $pages,'uid'=>$uid,'classtypeid'=>$classtypeid];
    }
}
