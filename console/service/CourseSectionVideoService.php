<?php
namespace console\service;

use Yii;
use common\redis\Cache;
use common\models\myb\CourseSectionVideo;

/**
* 课程章节视频相关方法
*/
class CourseSectionVideoService extends CourseSectionVideo
{
    /**
     * 更新价格后清缓存，用于双11活动
     */
    static function updatePrice_yizhao($price){
    	$redis = Yii::$app->cache;
    	$connection = Yii::$app->db;

    	$strsql = "update `myb_course_section_video` set `sale_price`=$price WHERE `status` =1 and `courseid` IN(select `courseid` FROM `myb_course` WHERE `status` =2 and `title` like '%一招%')";
    	$command = $connection->createCommand($strsql);
    	$data = $command->execute();
    	//清除缓存，因为是一次性操作，所以直接清全部缓存
    	$redis->flushdb();
    }
}