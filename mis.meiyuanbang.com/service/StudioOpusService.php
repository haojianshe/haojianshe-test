<?php

namespace mis\service;

use Yii;
use common\models\myb\StudioOpus;
use yii\data\Pagination;

#use common\service\dict\CourseDictDataService;

class StudioOpusService extends StudioOpus {

    /**
     * 分页获取画室列表
     */
    public static function getByPage($uid, $studiomenuid) {

        $query = (new \yii\db\Query())
                ->select("q.*")
                ->innerJoin('myb_studio_menu as b', 'q.studiomenuid=b.studiomenuid')
                ->from(parent::tableName() . ' as q')
                ->where(['q.status' => 1])
                ->andWhere(['b.studiomenuid' => $studiomenuid])
                // ->andWhere(['q.uid'=>$uid])
                ->andWhere(['q.status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select("q.*")
                ->innerJoin('myb_studio_menu as b', 'q.studiomenuid=b.studiomenuid')
                ->from(parent::tableName() . ' as q')
                ->where(['q.status' => 1])
                ->andWhere(['b.studiomenuid' => $studiomenuid])
                // ->andWhere(['q.uid'=>$uid])
                ->andWhere(['q.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('q.listorder DESC')
                #->createCommand()->getRawSql();
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'uid' => $uid, 'studiomenuid' => $studiomenuid];
    }

    /**
     * 删除缓存
     */
    public static function delCache($opus, $uid) {
        $redis = \Yii::$app->cache;
        $redis_key = "myb_studio_opus_" . $uid;
        $redis_key_opus = "studio_opus_" . $opus;
        $redis->delete($redis_key);
        $redis->delete($redis_key_opus);
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
