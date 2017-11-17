<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\Live;
use common\models\myb\ScanVideoRecord;

/**
 * 直播相关逻辑
 */
class LiveService extends Live {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($f_catalog_id = '', $s_catalog_id = '', $title = '', $start_time = '', $end_time = '') {
        # $query = parent::find()->where(['status' => 1]);
       
        $query = (new \yii\db\Query())->select('*')
                ->from(parent::tableName())
                ->where(['status' => 1]);
        if ($f_catalog_id) {
            $query->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $query->andWhere(['s_catalog_id' => $s_catalog_id]);
        }

        if ($title) {
            $query->andWhere(['like', 'live_title', $title]);
        }

        if ($start_time) {
            $query->andWhere(['>', 'start_time', strtotime($start_time)]);
        }
        if ($end_time) {
            $query->andWhere(['<', 'start_time', strtotime($end_time)]);
        }

        $query->offset(isset($pages->offset)?$pages->offset:0)
                ->limit(isset($pages->limit)?$pages->limit:0)->count();
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())->select('*')
                ->from(parent::tableName())
                ->where(['status' => 1]);
        if ($f_catalog_id) {
            $rows->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows->andWhere(['s_catalog_id' => $s_catalog_id]);
        }

        if ($title) {
            $rows->andWhere(['like', 'live_title', $title]);
        }

        if ($start_time) {
            $rows->andWhere(['>', 'start_time', strtotime($start_time)]);
        }
        if ($end_time) {
            $rows->andWhere(['<', 'start_time', strtotime($end_time)]);
        }

        $rows = $rows->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('liveid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 获取观看记录
     */
    public static function getLiveCanNum($f_catalog_id = '', $s_catalog_id = '', $title = '', $start_time = '', $end_time = '') {
        $count = 0;
        $rows = (new \yii\db\Query())->select('*')
                ->from(parent::tableName())
                ->select('liveid')
                ->where(['status' => 1]);
        if ($f_catalog_id) {
            $rows->andWhere(['f_catalog_id' => $f_catalog_id]);
        }
        if ($s_catalog_id) {
            $rows->andWhere(['s_catalog_id' => $s_catalog_id]);
        }

        if ($title) {
            $rows->andWhere(['like', 'live_title', $title]);
        }

        if ($start_time) {
            $rows->andWhere(['>', 'start_time', strtotime($start_time)]);
        }
        if ($end_time) {
            $rows->andWhere(['<', 'start_time', strtotime($end_time)]);
        }
        $rows = $rows->all();

        foreach ($rows as $k => $v) {
            $arr[] = $v['liveid'];
        }

        if ($arr) {
            $count = ScanVideoRecord::find()->where(['in', 'subjectid', $arr])->andWhere(['subjecttype' => 1])->count();
        }

        return $count;
    }

    /**
     * 重写save方法处理缓存
     * save包括新增 更新 删除3个情况
     * (non-PHPdoc)
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
//    public function save($runValidation = true, $attributeNames = NULL) {
//        $isnew = $this->isNewRecord;
//        $redis = Yii::$app->cache;
//        $ret = parent::save($runValidation, $attributeNames);
//        //处理缓存
//        if (!$isnew) {
//            //清除单个活动的缓存
//            $redis->delete($this->activity_detail_rediskey . $this->newsid);
//        }
//        $redis->delete($this->activity_list_rediskey);
//        return $ret;
//    }

    /**
     * 删除直播评论 --清除缓存
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function udpate_cmtcount($subjectid) {
        $redis = Yii::$app->cache;
        $redis_key = 'live_detail_' . $subjectid;
        $redis->delete('comment_list_' . $subjectid);
        $sql = "UPDATE `myb_live` SET `cmtcount` = `cmtcount`-1 WHERE `liveid` =  " . $subjectid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        $num = $redis->hincrby($redis_key, 'cmtcount', -1);
        if ($num < 0) {
            $redis->hset($redis_key, array('cmtcount' => 0));
        }
    }

    /**
     * 
     * @param type $liveid 直播id
     */
    public static function getLiveUrl($liveid) {
        return (new \yii\db\Query())
                        ->select('liveid,live_title,live_push_url,end_time,live_display_url')
                        ->from(parent::tableName())
                        ->where(['liveid' => $liveid])
                        ->andWhere(['status' => 1])
                        ->one();
    }

    /**
     * 清空缓存
     */
    public static function delCache($liveid, $uid) {
        #直播详情 "live_detail_".$liveid
        #直播列表 "live_list"
        #老师直播列表 "teacher_lives_".$uid
        $redis = Yii::$app->cache;
        $redis_live_key = 'live_detail_' . $liveid;
        $redis->delete($redis_live_key);
        $redis_list_key = 'live_list';
        $redis->delete($redis_list_key);
        $redis_teacher_key = 'teacher_lives_' . $uid;
        $redis->delete($redis_teacher_key);
    }

}
