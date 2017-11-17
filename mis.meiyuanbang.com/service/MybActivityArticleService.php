<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\MybActivityArticle;
use common\models\myb\News;
use common\models\myb\NewsData;

/**
 * 联考文章列表页面
 */
class MybActivityArticleService extends MybActivityArticle {

    /**
     * 分页获取所有联考文章列表页面
     */
    public static function getByPage($lkid, $type) {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_article as maa', 'mn.newsid=maa.newsid')
                ->where(['maa.activity_type' => 1])
                ->andWhere(['mn.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $models['data'] = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_article as maa', 'mn.newsid=maa.newsid')
                ->where(['maa.activity_type' => 1])
                ->andWhere(['mn.status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        $models['lkid'] = $lkid;
        $models['zp_type'] = $type;
        $models['lkids'] = self::getSelectData($lkid, $type);
        $models['zhiding'] = self::getSelected($lkid, $type);
        return ['models' => $models, 'pages' => $pages];
    }

    /*
     * 获取已经选中的数据
     */

    public static function getSelectData($lkid, $type) {
        $data = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime,mlmr.status,mlmr.ctime as ptime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_article as maa', 'mn.newsid=maa.newsid')
                ->innerJoin('myb_lk_material_relation as mlmr', 'mlmr.newsid=maa.newsid')
                ->innerJoin('myb_lk as ml', 'ml.lkid=mlmr.lkid')
                ->where(['ml.lkid' => $lkid])
                ->andWhere(['mn.status' => 0])
                ->andWhere(['in', 'mlmr.zp_type', $type])
                ->all();
        return $data;
    }

    /*
     * 获取已经选中的置顶
     */

    public static function getSelected($lkid, $type) {
        $data = (new \yii\db\Query())
                ->select('mn.newsid,mn.title,mn.username,mn.ctime,mlmr.zp_type,mlmr.zdtime,mlmr.status,mlmr.ctime as ptime')
                ->from('myb_news as mn')
                ->innerJoin('myb_activity_article as maa', 'mn.newsid=maa.newsid')
                ->innerJoin('myb_lk_material_relation as mlmr', 'mlmr.newsid=maa.newsid')
                ->innerJoin('myb_lk as ml', 'ml.lkid=mlmr.lkid')
                ->where(['ml.lkid' => $lkid])
                ->andWhere(['mn.status' => 0])
                ->andWhere(['in', 'mlmr.zp_type', $type])
                ->all();
        return $data;
    }

    /**
     * 获取文章列表
     */
    public static function getActiveData() {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid')
                ->where(['b.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' =>50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.articleid', 'a.activity_type', 'b.*', 'c.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_news as b', 'a.newsid=b.newsid')
                ->innerJoin('myb_news_data as c', 'a.newsid=c.newsid')
                ->where(['b.status' => 0])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.articleid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

}
