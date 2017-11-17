<?php

namespace api\service;

use Yii;
use common\models\myb\CourseSharelotto;
use api\service\CourseService;
/**
 * 
 * @author ihziluoh
 * 
 * 课程分享抽奖记录
 */
class CourseSharelottoService extends CourseSharelotto {
    /**
        得到今天的分享记录
    */
    public static function IsShowPrize($uid,$courseid){
        $detail=CourseService::find()->where(['courseid'=>$courseid])->asArray()->one();
        if($detail['gameid'] && $detail['game_start_time']<time() && $detail['game_end_time']>time()){
            $dateStr = date('Y-m-d', time()); 
            $start_time= strtotime($dateStr);
            $end_time=strtotime($dateStr)+86400;
            $ret=self::find()->where(['uid'=>$uid])->andWhere(['courseid'=>$courseid])->andWhere([">","ctime",$start_time])->andWhere(['<','ctime',$end_time])->asArray()->all();
            if(count($ret)>=3){
              return false;
            }else{
              return true;
            }
        }else{
            return false;
        }


        
    }
    /**
        增加分享记录
    **/
    public static function addSharelotto($courseid,$uid,$type){
        $dateStr = date('Y-m-d', time()); 
        $start_time= strtotime($dateStr);
        $end_time=strtotime($dateStr)+86400;
        $find=self::find()->where(['uid'=>$uid])->andWhere(['courseid'=>$courseid])->andWhere(['type'=>$type])->andWhere([">","ctime",$start_time])->andWhere(['<','ctime',$end_time])->one();
        if($find){
            return 2;
        }
        $model=new CourseSharelotto();
        $model->courseid=$courseid;   
        $model->uid=$uid;
        $model->type=$type;   
        $model->ctime=time();  
        $ret=$model->save();
        if($ret){
            return 1;
        }else{
            return 0;
        }
    }
}
