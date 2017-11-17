<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Lesson;

/**
 * 考点相关逻辑
 */
class LessonService extends Lesson {

    //考点列表 lesson_newsids_$maintype_$subtype
    private $lesson_list_rediskey = 'lesson_newsids_';
    //考点详情 lesson_detail_ +考点id
    private $lesson_detail_rediskey = 'lesson_detail_';

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($title = '', $f_catalog_id = '', $s_catalog_id = '') {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                ->where(['<>', 'status', 1]);
        if ($title) {
            $query->andWhere(['like', 'title', $title]);
        }
        if ($f_catalog_id) {
            $query->andWhere(['maintype' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['subtype' => $s_catalog_id]);
        };
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName())
                ->where(['<>', 'status', 1]);
        if ($title) {
            $rows->andWhere(['like', 'title', $title]);
        }
        if ($f_catalog_id) {
            $rows->andWhere(['maintype' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows->andWhere(['subtype' => $s_catalog_id]);
        }
        $rows = $rows->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('lessonid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取总条数 
     */
    public static function getLessonCount($title = '', $f_catalog_id = '', $s_catalog_id = '') {
        $rows = self::find()
                ->select('sum(hits) as hits')
                ->from(parent::tableName())
                ->where(['<>', 'status', 1]);
        if ($title) {
            $rows->andWhere(['title' => $title]);
        }
        if ($f_catalog_id) {
            $rows->andWhere(['maintype' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows->andWhere(['subtype' => $s_catalog_id]);
        }
        $rows = $rows->asArray()->one();
        return $rows['hits'];
    }

    /**
     * 重载model的save方法，保存后处理缓存
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        //处理缓存
        //发布 取消发布 删除 新增 都要清掉对应的list缓存，保证前台同步更新
        $rediskey = $this->lesson_list_rediskey . $this->maintype . '_' . $this->subtype;
        $redis->delete($rediskey);
        //编辑时，清除掉掉单个记录的缓存
        if ($isnew == false) {
            $rediskey = $this->lesson_detail_rediskey . $this->lessonid;
            $redis->delete($rediskey);
        }
        return $ret;
    }

    /**
     * 删除考点评论方法 处理缓存
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function udpate_cmtcount($subjectid) {
        $redis = Yii::$app->cache;
        $redis_key = 'lesson_detail_' . $subjectid;
        $sql = "UPDATE `myb_lesson` SET `cmtcount` = `cmtcount`-1 WHERE `lessonid` =  " . $subjectid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        $num = $redis->hincrby($redis_key, 'cmtcount', -1);
        if ($num < 0) {
            $redis->hset($redis_key, array('cmtcount' => 0));
        }
    }

}
