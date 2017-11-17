<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\LectureTag;

/**
 * 精讲主题相关逻辑
 */
class LectureTagService extends LectureTag {

    /**
     * 查询每一条专题下的tag
     * @param  [int]      $newid   [主题id]
     * @return [type]               [description]
     */
    public static function getAddtagPage($newid) {
        $query = (new \yii\db\Query())
                ->select(['a.*', 'b.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_lecture_tag as b', 'a.newsid=b.newsid')
                ->where(['a.newstype' => 2])
                ->andWhere(['a.newsid' => $newid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select(['a.*', 'b.*'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_lecture_tag as b', 'a.newsid=b.newsid')
                ->where(['a.newstype' => 2])
                ->andWhere(['a.newsid' => $newid])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.newsid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
//        $redis->delete("course_detail_" . $this->courseid);
//        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id);
//        $redis->delete("course_list_" . $this->f_catalog_id . "_0");
//        $redis->delete("teacher_course_list" . $this->teacheruid);
        //$redis->delete($this->lecture_list_rediskey);
        $redis->delete("lecture_detail_new_" . $this->newsid);
        $redis->delete("lecture_subject_detail_" . $this->newsid);
        return $ret;
    }

}
