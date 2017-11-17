<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybActivityQa;
/**
 * 联考问答列表页面
 */
class QaService extends MybActivityQa {

    /**
     * 分页获取所有联考问答列表页面
     */
    public static function getByPage($lkid) {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_qa as maq', 'mn.newsid=maq.newsid')
                ->where(['maq.activity_type' => 1])
                ->andWhere(['mn.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);

        $models['data'] = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_qa as maq', 'mn.newsid=maq.newsid')
                ->where(['maq.activity_type' => 1])
                ->andWhere(['mn.status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        $models['lkid'] = $lkid;
        $models['lkids'] = self::getSelectData($lkid);
        $models['zhiding'] = self::getSelected($lkid);
        return ['models' => $models, 'pages' => $pages];
    }

    /*
     * 获取已经选中的数据
     */

    public static function getSelectData($lkid) {
        $models = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime,mlmr.status,mlmr.ctime as ptime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_qa as maq', 'mn.newsid=maq.newsid')
                ->innerJoin('myb_lk_material_relation as mlmr', 'mlmr.newsid=maq.newsid')
                ->innerJoin('myb_lk as ml', 'ml.lkid=mlmr.lkid')
                ->where(['mlmr.zp_type' => 1])
                ->andWhere(['ml.lkid' => $lkid])
                ->andWhere(['mn.status' => 0])
                ->all();
        return $models;
    }

    /*
     * 获取已经选中的置顶
     */

    public static function getSelected($lkid) {
        $models = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime,mlmr.zdtime,mlmr.status,mlmr.ctime as ptime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_qa as maq', 'mn.newsid=maq.newsid')
                ->innerJoin('myb_lk_material_relation as mlmr', 'mlmr.newsid=maq.newsid')
                ->innerJoin('myb_lk as ml', 'ml.lkid=mlmr.lkid')
                ->where(['mlmr.zp_type' => 1])
                ->andWhere(['ml.lkid' => $lkid])
                ->andWhere(['mn.status' => 0])
                ->orderBy('zdtime desc')
                ->all();
        return $models;
    }

    /**
     * 获取文章列表
     */
    public static function getQuestionsData() {
        # $query = parent::find();
        $query = (new \yii\db\Query())
                ->select(['a.ask_limit', 'a.answer_uids', 'a.cover_type', 'a.activity_type', 'a.ctime', 'b.*', 'c.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid')
                ->where(['b.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.ask_limit', 'a.answer_uids', 'a.cover_type', 'a.activity_type', 'a.ctime', 'b.*', 'c.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid')
                ->where(['b.status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.qaid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

}
