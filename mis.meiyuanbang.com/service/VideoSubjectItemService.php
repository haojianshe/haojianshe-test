<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\VideoSubjectItem;

class VideoSubjectItemService extends VideoSubjectItem {

    /**
     * 分页获取一招数据列表
     */
    public static function getByPage($subid) {
        $query = (new \yii\db\Query())
                ->select(['*'])
                ->from(parent::tableName())
                ->where(['subjectid' => $subid]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据	
        $rows = (new \yii\db\Query())
                ->select(['b.courseid', 'b.title', 'b.thumb_url', 'b.teacher_desc', 'b.teacheruid', 'a.ctime', 'a.subjectid', 'a.itemid', 'a.listorder'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_course as b', 'b.courseid=a.courseid')
                ->where(['a.subjectid' => $subid])
                ->andWhere(['b.status' => 2])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.listorder DESC,a.ctime DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages, 'subjectid' => $subid];
    }

    /**
     * 获取专题下面指定的id
     * @param type $subjectid 视频专题id
     */
    public static function getCoures($subjectid) {
        //获取已经存在的课程id
        $res = self::find()->select('courseid')->where(['subjectid' => $subjectid])->asArray()->all();
        $array = [];
        if ($res) {
            foreach ($res as $key => $val) {
                $array[$key] = $val['courseid'];
            }
        }
        return $array;
    }

    /**
     * 获取所有的课程
     */
    public static function getCouresList($subjectid, $type = '') {
        $query = (new \yii\db\Query())
                ->select(['courseid', 'title', 'thumb_url', 'ctime'])
                ->from('myb_course')
                ->where(['status' => 2]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);

        $rows = (new \yii\db\Query())
                ->select(['courseid', 'title', 'thumb_url', 'ctime', 'teacher_desc'])
                ->from('myb_course')
                # ->innerJoin('myb_course as b', 'b.courseid=a.courseid')
                ->where(['status' => 2])
                # ->andWhere(['b.status' => 2])
                ->offset($pages->offset)
                ->limit($pages->limit);
        if ($type) {
            $rows->orderBy('myb_course.ctime DESC');
        }
        $rows = $rows->all();
        return ['models' => $rows, 'pages' => $pages, 'subjectid' => $subjectid];
        #return self::find()->from('myb_course')->select(['courseid', 'title', 'thumb_url', 'ctime'])->where(['status' => 2])->asArray()->all();
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
        $redis->delete('video_subject_' . $this->subjectid); //删除一招下面课程列表缓存
        return $ret;
    }

}
