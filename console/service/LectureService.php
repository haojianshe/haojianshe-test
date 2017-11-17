<?php
namespace console\service;

use Yii;
use common\models\myb\Lecture;
use common\redis\Cache;

/**
 * 精讲相关逻辑
 */
class LectureService extends Lecture
{    
    /**
     * 获取已到定时发布时间的帖子id
     */
    static function getByPublishTime($time){
    	$ids = (new \yii\db\Query())
    	->select(['newsid'])
    	->from(parent::tableName())
    	->where(['<>','status',1])
    	->andWhere(['>','publishtime',0])
    	->andWhere(['<','publishtime',$time])
    	->all();
    	return $ids;
    }
    
    /**
     * 更新列表缓存
     */
    static function removeListCache(){
    	$lecture_list_rediskey = 'lecture_newsids';
    	$redis = Yii::$app->cache;
    	
    	$redis->delete($lecture_list_rediskey);
    }
    
    /**
     * 改变lecture表的newsid,并且发布
     * @param unknown $newsid
     * @param unknown $oldnewsid
     */
    static function publish($newsid,$oldnewsid){
        $sql="UPDATE `myb_lecture` SET `newsid` = $newsid ,publishtime=0  WHERE `newsid` =  $oldnewsid";
        $connection = Yii::$app->db; //连接
        $command_count = $connection->createCommand($sql);
        $command_count->query();
    }
}