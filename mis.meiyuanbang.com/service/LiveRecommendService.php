<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\LiveRecommend;

/**
 * 直播相关逻辑
 */
class LiveRecommendService extends LiveRecommend {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage() {
        $query = parent::find();
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('*')
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_live as b', 'a.liveid=b.liveid')
                ->where(['b.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.liveid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
    }

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPageList() {
        $query = (new \yii\db\Query())
                ->select('*')
                ->from('myb_live as a')
                ->leftJoin('myb_live_recommend as b', 'a.liveid=b.liveid')
                ->where(['is', 'b.liveid', null])->andWhere(['a.status' => 1]);
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        //获取数据    	
        $rows = (new \yii\db\Query())
                ->select('a.*')
                ->from('myb_live as a')
                ->leftJoin('myb_live_recommend as b', 'a.liveid=b.liveid')
                ->where(['is', 'b.liveid', null])
                ->andWhere(['a.status' => 1])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.liveid DESC')
                ->all();
        return ['models' => $rows, 'pages' => $pages];
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
     * 删除活动评论 --清除缓存
     * @param  [type] $subjectid [description]
     * @return [type]            [description]
     */
    public static function udpate_cmtcount($subjectid) {
        $redis = Yii::$app->cache;
        $redis_key = 'activity_detail_' . $subjectid;
        $sql = "UPDATE `myb_news_data` SET `cmtcount` = `cmtcount`-1 WHERE `newsid` =  " . $subjectid;
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
        $num = $redis->hincrby($redis_key, 'cmtcount', -1);
        if ($num < 0) {
            $redis->hset($redis_key, array('cmtcount' => 0));
        }
    }

}
