<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\VideoSubject;

class VideoSubjectService extends VideoSubject {

    /**
     * 分页获取一招数据列表
     */
    public static function getByPage() {
        $query = (new \yii\db\Query())
                ->select(['*'])
                ->from(parent::tableName())
                ->where(['<>', 'status', 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据	
        $rows = (new \yii\db\Query())
                ->select(['*'])
                ->from(parent::tableName())
                ->where(['<>', 'status', 2])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('ctime DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 删除课程评论 --清除缓存
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function udpate_cmtcount($subjectid) {
        $redis = Yii::$app->cache;
        $redis_key = 'course_detail_' . $subjectid;
        $sql = "UPDATE `myb_course` SET `cmtcount` = `cmtcount`-1 WHERE `courseid` =  " . $subjectid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        $num = $redis->hincrby($redis_key, 'cmtcount', -1);
        if ($num < 0) {
            $redis->hset($redis_key, array('cmtcount' => 0));
        }
    }

    /**
     * 保存前处理缓存
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        #已经审核状态下清除缓存
        if ($this->status < 3) {
            $redis->delete("video_subject_list");
            $redis->delete("video_subject_" . $this->subjectid);
        }
        return $ret;
    }

}
