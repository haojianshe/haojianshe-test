<?php

namespace mis\service;

use Yii;
use common\models\myb\Course;
use yii\data\Pagination;
use common\service\dict\CourseDictDataService;
use common\models\myb\ScanVideoRecord;

class CourseService extends Course {

    /**
     * 分页获取列表
     */
    public static function getByPage($f_catalog_id = null, $s_catalog_id = null, $status = null, $title = '', $start_time = '', $end_time = '') {

        $query = parent::find()->where(['<>', 'status', 3]);
        if ($f_catalog_id) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        if ($start_time) {
            $query->andWhere(['>', 'ctime', strtotime($start_time)]);
        }
        if ($end_time) {
            $query->andWhere(['<', 'ctime', strtotime($end_time)]);
        }

        if ($status) {
            $query->andWhere(['status' => $status]);
        }

        if ($title) {
            $query->andWhere(['like', 'title', $title]);
        }

        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("*")
                ->innerJoin('ci_user_detail as b', 'teacheruid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['<>', 'q.status', 3]);
        if ($f_catalog_id) {
            $rows_query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows_query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        if ($status) {
            $rows_query->andWhere(['q.status' => $status]);
        }
        if ($title) {
            $rows_query->andWhere(['like', 'q.title', $title]);
        }

        if ($start_time) {
            $rows_query->andWhere(['>', 'ctime', strtotime($start_time)]);
        }
        if ($end_time) {
            $rows_query->andWhere(['<', 'ctime', strtotime($end_time)]);
        }


        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('courseid DESC')#->createCommand()->getRawSql();
                ->all();

        foreach ($rows as $key => $value) {
            $rows[$key]['f_catalog'] = CourseDictDataService::getCourseMainTypeNameById($rows[$key]['f_catalog_id']);
            $rows[$key]['s_catalog'] = CourseDictDataService::getCourseSubTypeById($rows[$key]['f_catalog_id'], $rows[$key]['s_catalog_id']);
        }
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
        $redis->delete('studio_studio_course_' . $this->teacheruid); //删除老师课程
        $redis->delete("course_detail_" . $this->courseid);
        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id);
        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id . "_3");
        $redis->delete("course_list_" . $this->f_catalog_id . "_" . $this->s_catalog_id . "_1");
        $redis->delete("course_list_" . $this->f_catalog_id . "_0");
        $redis->delete("teacher_course_list" . $this->teacheruid);
        $redis->delete("studio_coures_list");
        $redis->delete("studio_studio_course" . $this->teacheruid);
        return $ret;
    }

    /**
     * 查看课程观看人次
     * 
     */
    public static function CourseCanNum($courseid = '', $type = 2, $start_time = '', $end_time = '', $status = '') {
        $where = '';
        if ($start_time) {
            if ($status == 1) {
                $where = ' and ctime<' . $end_time; #and ctime>=' . $start_time . ' 
            } else {
                $where = ' and ctime>=' . $end_time;
            }
        }
        $connection = Yii::$app->db; //连接
        if ($courseid) {
            $sql = "select count(*) as count from myb_scan_video_record where subjectid=$courseid and subjecttype=$type and  (uid<500 or uid>1000) $where";
        } else {
            $sql = "select count(*) as count from myb_scan_video_record where subjecttype=$type and (uid<500 or uid>1000) $where";
        }
        $command_count = $connection->createCommand($sql);
        return $command_count->queryAll()[0]['count'];
    }

    /**
     * 获取观看记录
     */
    public static function getCourseCanNum($f_catalog_id = '', $s_catalog_id = '', $title = '', $start_time = '', $end_time = '') {
        $count = 0;
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("q.courseid")
                ->innerJoin('ci_user_detail as b', 'teacheruid=b.uid')
                ->from(parent::tableName() . ' as q')
                ->where(['<>', 'q.status', 3]);
        if ($f_catalog_id) {
            $rows_query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows_query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }
        if ($title) {
            $rows_query->andWhere(['like', 'q.title', $title]);
        }

        if ($start_time) {
            $rows_query->andWhere(['>', 'ctime', strtotime($start_time)]);
        }
        if ($end_time) {
            $rows_query->andWhere(['<', 'ctime', strtotime($end_time)]);
        }

        $rows = $rows_query->all();

        foreach ($rows as $k => $v) {
            $arr[] = $v['courseid'];
        }
        if ($arr) {
            $count = ScanVideoRecord::find()->where(['in', 'subjectid', $arr])->andWhere(['subjecttype' => 2])->count();
        }
        return $count;
    }

    /**
     * 获取所有的课
     * 10月12号修改 以前获取整课购买
     */
    public static function getCourseByPage($title = '') {
        #->andWhere(['buy_type' => 2])
        $query = parent::find()->where(['status' => 2]);
        if ($title) {
            $query->andWhere(['like', 'title', $title]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows_query = (new \yii\db\Query())
                ->select("*")
                ->from(parent::tableName())
                ->where(['status' => 2]);
        # ->andWhere(['buy_type' => 2])
        if ($title) {
            $rows_query->andWhere(['like', 'title', $title]);
        }
        $rows = $rows_query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('courseid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

}
