<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\TurntablePrizeGame;

/**
 * 活动相关逻辑
 */
class TurntablePrizeGame extends TurntablePrizeGame {

    /**
     * 分页获取所有抽奖活动列表
     * @return array 返回数据按照gameid倒序排序
     */
    public static function getByPage() {
        $query = parent::find()->where(['status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 15]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                # ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->where(['status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('gameid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

}
