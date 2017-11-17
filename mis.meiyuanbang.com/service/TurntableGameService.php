<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\TurntableGame;

/**
 * 活动相关逻辑
 */
class TurntableGameService extends TurntableGame {

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
                ->where(['status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('gameid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 抽奖活动是否存在
     * @param int $gameid 抽奖活动id
     * @return int count
     */
    public static function getGameCount($gameid) {
        return self::find()->where(['gameid' => $gameid])->count();
    }

    /**
     * 分页获取所有获奖名单
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序
     */
    public static function getUserListData($gameid, $start_time = '', $end_time = '', $type = '', $title = '') {
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select(['b.name', 'b.mobile', 'c.type', 'b.address', 'b.ctime', 'c.title', 'c.img', 'b.uid'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_turntable_prize_user as b', 'a.gameid=b.gameid')
                ->innerJoin('myb_turntable_prize as c', 'c.prizesid=b.prizesid')
                ->where([ 'a.gameid' => $gameid]);
        if ($type) {
            $rows_query->andWhere(['c.type' => $type]);
        }
        if ($start_time) {
            $rows_query->andWhere(['>', 'b.ctime', strtotime($start_time)]);
        }
        if ($title) {
            $rows_query->andWhere(['c.title' => $title]);
        }
        if ($end_time) {
            $rows_query->andWhere(['<', 'b.ctime', strtotime($end_time)]);
        }
        $countQuery = clone $rows_query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 30]);
        $rows = $countQuery->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('b.prizeuserid DESC')
                ->all();

        return ['models' => $rows, 'pages' => $pages];
    }
    
     /**
     * 获取奖品列表
     */
    public static function getTitle($activityid) {
        return  (new \yii\db\Query())
                ->select('b.title')
                ->from('myb_turntable_game_prizes as a')
                ->innerJoin('myb_turntable_prize as b', 'a.prizesid=b.prizesid')
                ->where(['a.gameid' => $activityid])
                #->createCommand()->getRawSql();
               ->all();
    }
    
    

}
