<?php
namespace console\controllers\course;

use Yii;
use yii\base\Action;
use console\service\CourseSectionVideoService;
use console\service\CourseService;
/**
 * 双11课程定时降价
 */
class Double11Action extends Action
{      
	public  function run() {
		$this->yizhao();
		$this->zhuangyuan();           
    }
    
    /**
     * 双11一招降价
     */
    private function yizhao(){
    	$now=time();
    	//双11 降价
    	if($now >strtotime(date('2017-11-10 00:00:00', time())) && $now<strtotime(date('2017-11-10 00:30:00', time()))){
    		//减价操作对应的缓存和恢复原价对应的缓存
    		$redis = Yii::$app->cache;
    		$update_course_redis="course11_update_price_day";
    		//判断是否执行过
    		if( $redis->get($update_course_redis)!="on"){
    			//降价,清整站缓存
    			CourseSectionVideoService::updatePrice_yizhao(4.8);
    			//记降价操作缓存
    			$redis->set($update_course_redis,"on");
    			$redis->expire($update_course_redis, 60 * 60 * 2);
    		}
    		return;
    	}
    	//双11后恢复原价
    	if($now >strtotime(date('2017-11-13 00:00:00', time())) && $now<strtotime(date('2017-11-13 00:30:00', time()))){
    		//减价操作对应的缓存和恢复原价对应的缓存
    		$redis = Yii::$app->cache;
    		$reset_course_redis="course11_reset_price_day";
    		//判断是否执行过
    		if( $redis->get($reset_course_redis)!="on"){
    			//还原价格，清整站缓存
    			CourseSectionVideoService::updatePrice_yizhao(6);
    			//记还原缓存
    			$redis->set($update_course_redis,"on");
    			$redis->expire($update_course_redis, 60 * 60 * 2);
    		}
    		return;
    	}
    }
    
    /**
     * 双11状元笔记降价活动
     */
    private function zhuangyuan(){
    	$now=time();
    	//双11 降价
    	if($now >strtotime(date('2017-11-11 23:00:00', time())) && $now<strtotime(date('2017-11-12 00:00:00', time()))){
    		//减价操作对应的缓存和恢复原价对应的缓存
    		$redis = Yii::$app->cache;
    		$update_course_redis="course11_zhuangyuan_update_price_day";
    		//判断是否执行过
    		if( $redis->get($update_course_redis)!="on"){
    			//降价,清整站缓存
    			CourseService::updatePrice_double11(99);
    			//记降价操作缓存
    			$redis->set($update_course_redis,"on");
    			$redis->expire($update_course_redis, 60 * 60 * 2);
    		}
    		return;
    	}
    	//双11后恢复原价
    	if($now >strtotime(date('2017-11-12 00:00:00', time())) && $now<strtotime(date('2017-11-12 00:30:00', time()))){
    		//减价操作对应的缓存和恢复原价对应的缓存
    		$redis = Yii::$app->cache;
    		$reset_course_redis="course11_zhuangyuan_reset_price_day";
    		//判断是否执行过
    		if( $redis->get($reset_course_redis)!="on"){
    			//还原价格，清整站缓存
    			CourseService::updatePrice_double11(298);
    			//记还原缓存
    			$redis->set($update_course_redis,"on");
    			$redis->expire($update_course_redis, 60 * 60 * 2);
    		}
    		return;
    	}
    }


}
