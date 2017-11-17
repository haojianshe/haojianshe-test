<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use common\models\myb\GroupBuy;
use common\models\myb\Orderinfo;

/**
 * 活动相关逻辑
 */
class GroupbuyService extends GroupBuy {

    /**
     * 分页获取所有精讲列表
     * 获取数据总数时，不需要加排序条件
     * 返回数据按照id倒序排序     * 
     */
    public static function getByPage($start_time, $end_time, $status = '') {
        $query = (new \yii\db\Query())
                ->select(['a.*', 'b.title as courseTitle'])
                ->from(parent::tableName() . ' as a')
                ->innerJoin('myb_course as b', 'a.courseid=b.courseid')
                ->where(['a.status' => 1])
                ->andWhere(['b.status' => 2])
                ->andWhere(['b.buy_type' => 2]);
        if ($start_time) {
            $query->andWhere(['>', 'start_time', $start_time]);
            $query->andWhere(['<', 'end_time', $end_time]);
        }
        $countQuery = clone $query;
        //分页对象计算分页数据
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $data = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('a.groupbuyid DESC')
                ->all();
        if ($data) {
            foreach ($data as $k => $v) {
                $array[$k] = $v['groupbuyid'];
            }
        }
        $array['start_time'] = $start_time;
        $array['end_time'] = $end_time;
        $array['orderSum'] = self::getOrderSum($countQuery);
      
        return ['models' => $data, 'pages' => $pages, 'data' => $array];
    }

    /**
     * 获取订单总金额
     */
    public static function getOrderSum($countQuery) {
       $arr =  $countQuery->all();
        return Orderinfo::find()->select('sum(fee) as fee')->where(['status' => 1])->andWhere(['in', 'groupbuyid', $arr])->one();
    }

    /**
     * 重写save方法处理缓存
     * save包括新增 更新 删除3个情况
     * (non-PHPdoc)
     * @see \yii\db\BaseActiveRecord::save($runValidation, $attributeNames)
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $redis = Yii::$app->cache;
        $ret = parent::save($runValidation, $attributeNames);
        //处理缓存
//        if (!$isnew) {
//            //清除单个活动的缓存
//            $redis->delete($this->activity_detail_rediskey . $this->newsid);
//        }
//        $redis->delete($this->activity_list_rediskey);
        return $ret;
    }

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
