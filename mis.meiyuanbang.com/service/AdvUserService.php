<?php

namespace mis\service;

use Yii;
use common\models\myb\AdvUser;
use yii\data\Pagination;

class AdvUserService extends AdvUser {
    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage() {
        $query = parent::find()->where(["status"=>2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据      
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() )
                ->where(['status' => 2])
                ->offset($pages->offset)
                ->limit($pages->limit)
                //->orderBy('sort_id DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }
}
