<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybLk;

/**
 * 获取联考活动列表
 */
class LkActivityService extends MybLk {

    /**
     * 分页获取联考活动列表
     */
    public static function getByPage() {
        $query = parent::find()->where(['status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.lkid', 'a.title', 'a.ctime', 'a.btime', 'a.rank_status', 'b.mis_realname'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_mis_user as b', 'a.adminid=b.mis_userid')
                ->where(['a.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.lkid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

}
