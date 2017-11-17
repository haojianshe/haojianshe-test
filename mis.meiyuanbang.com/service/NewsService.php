<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\News;

/**
 * cms内容页相关逻辑
 * 目前精讲 课程 活动共用
 */
class NewsService extends News {

    /**
     * 获取某一个类型内容的条数
     * @param unknown $type 精讲 课程还是活动
     */
    public static function getCount($type = null) {
        $query = parent::find()->where(['status' => 0]);
        if ($type) {
            $query = $query->andWhere(['catid' => $type]);
        }
        return $query->count();
    }

    /**
     * 获取评论
     * @param type $newsid
     * @return type
     */
    public static function getByPage($newsid) {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from('myb_news as mn')
                ->innerJoin('eci_comment as ec', 'ec.subjectid=mn.newsid')
                ->leftJoin('ci_user_detail as cud', 'ec.uid=cud.uid')
                ->where(['mn.newsid' =>$newsid])
                ->andWhere(['ec.is_del' => 0])
                ->andWhere(['mn.status' => 0]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        $models = (new \yii\db\Query())
                ->select(['ec.cid','ec.content','ec.subjecttype','ec.ctime','ec.cid','cud.sname'])
                ->from('myb_news as mn')
                ->innerJoin('eci_comment as ec', 'ec.subjectid=mn.newsid')
                ->leftJoin('ci_user_detail as cud', 'ec.uid=cud.uid')
                ->where(['mn.newsid' =>$newsid])
                ->andWhere(['ec.is_del' => 0])
                ->andWhere(['mn.status' => 0])
                 ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        return ['models' => $models, 'pages' => $pages];
    }

}
