<?php
namespace console\controllers\course;

use Yii;
use yii\base\Action;
use console\service\CourseService;

/**
 * 课程价格定时降价功能
 */
class UpdatePriceTempAction extends Action
{      // */1 * * * *   /home/web/backcode/pushservice course/update_price_temp &
       public  function run() {
            // 美院帮状元笔记课程于9月22日正式开始售卖，课程售卖期间，每晚21：30-22：30这段时间内，已上线课程整体价格全部调整为198元 课程价格调整周期：2017年9月22日-10月8日晚24点 241-246 courseid
            $now=time();
            if($now >strtotime(date('2017-10-14 00:00:00', time())) && $now<strtotime(date('2017-10-16 00:00:00', time()))){
            	//要改变价格的课程id数组
            	$courseids=[241,242,243,244,245,246,254,256];               
                //减价操作对应的缓存和恢复原价对应的缓存
            	$redis = Yii::$app->cache;
                $update_course_redis="course_update_price_day".strtotime(date('Y-m-d', time()));
                $reset_course_redis="course_reset_price_day".strtotime(date('Y-m-d', time()));


                $day_stime=strtotime(date('Y-m-d 22:00:00', time()));  
                $day_etime=strtotime(date('Y-m-d 23:59:59', time()));
                
				//未到活动时间则退出
				if($now<$day_stime){
					return;
				}
                //判断是否在活动区间
                if($now>$day_stime && $now <$day_etime){
                        //判断是否执行过
                        if( $redis->get($update_course_redis)!="on"){
                            //降价
                            $this->updatePriceByCourseids($courseids,148);
                            //记缓存
                            $redis->set($update_course_redis,"on");
                            $redis->expire($update_course_redis, 60 * 60 * 2);
                            //清除课程详情缓存
                            $this->clearCourseDetailRedis($courseids);
                        }
                        return;
                    }
                    //活动结束
                    if($now >$day_etime)
                    {
                        //判断是否执行过
                        if($redis->get($reset_course_redis)!="on"){
                        		//还原价格
                        		$this->updatePriceByCourseids($courseids,298);
                                //记缓存
                        		$redis->set($reset_course_redis,"on");
                                $redis->expire($reset_course_redis, 60 * 60 * 2);
                                //清课程详情缓存
                                $this->clearCourseDetailRedis($courseids);
                        }
                    }
                }
               
        }

       /**
            更改价格
       **/
       private function updatePriceByCourseids($courseid_arr,$price=198){
           CourseService::updateAll(['course_sale_price' => $price,'course_price_ios'=> $price], ['in','courseid',$courseid_arr]);

       }
       /**
            还原价格
       **/
        private function updatePriceByCourseid($courseid,$course_sale_price,$course_price_ios){
            $model=CourseService::find()->where(['courseid'=>$courseid])->one();
            $model->course_sale_price=$course_sale_price;
            $model->course_price_ios=$course_price_ios;
            $model->save();
        }
        /**
            清除课程缓存
        **/
        private function clearCourseDetailRedis($courses_arr){
            $redis = Yii::$app->cache;
            foreach ($courses_arr as $key => $value) {
                $redis->delete("course_detail_" . $value);
            }
        }

}
