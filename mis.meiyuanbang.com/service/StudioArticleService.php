<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioArticle;
use yii\data\Pagination;

//use common\service\dict\CourseDictDataService;

class StudioArticleService extends StudioArticle {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($studiomenuid) {

        $query = (new \yii\db\Query())
                ->select("b.title,a.*")
                ->innerJoin('myb_news as b', 'b.newsid=a.newsid')
                ->from(parent::tableName() .' as a')
                ->where(['a.studiomenuid' => $studiomenuid])
                ->andWhere(['a.status' => 1])
                ->andWhere(['b.catid' => 7]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("b.title,a.*")
                ->innerJoin('myb_news as b', 'b.newsid=a.newsid')
                ->from(parent::tableName() .' as a')
                ->where(['a.studiomenuid' => $studiomenuid])
                ->andWhere(['a.status' => 1])
                ->andWhere(['b.catid' => 7])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('listorder DESC')
                ->all();
        
        return ['models' => $rows, 'pages' => $pages,'studiomenuid'=>$studiomenuid];
    }

    /**
     * 保存前处理缓存
     */
//    public function save($runValidation = true, $attributeNames = NULL) {
//        $isnew = $this->isNewRecord;
//        $redis = Yii::$app->cache;
//        $ret = parent::save($runValidation, $attributeNames);
//        $redis->delete("course_detail_" . $this->courseid);
//        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id);
//        $redis->delete("course_list_" . $this->f_catalog_id . "_0");
//        $redis->delete("teacher_course_list" . $this->teacheruid);
//        //$redis->delete($this->lecture_list_rediskey);
//        return $ret;
//    }
}
