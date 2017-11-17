<?php

namespace api\service;

use Yii;

use common\models\myb\VideoSubject;
use api\service\VideoSubjectItemService;
/**
 * 
 * @author ihziluoh
 * 
 * 视频专题
 */
class VideoSubjectService extends VideoSubject{

    
    public static function getVideoSubjectList($lastid=NULL,$rn=50,$subject_type=0){
        $redis = Yii::$app->cache;
        $rediskey="video_subject_list_".$subject_type;
        // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getVideoSubjectListDb($subject_type);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['subjectid'].',';
                $ret = $redis->rpush($rediskey, $value['subjectid'],true);
            }
            $redis->expire($rediskey,3600*24*3);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        //分页数据获取
        if(empty($lastid)){
            $idx=0;
            $ids_data=$redis->lrange($rediskey,0, $rn-1);
        }else{
            $idx = array_search($lastid, $list_arr);
            $ids_data = array_slice($list_arr,$idx+1,$rn);
            //$ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
        }
        return $ids_data;
    }
    /**
     * 数据库获取列表
     * @return [type] [description]
     */
    public static function getVideoSubjectListDb($subject_type){
        $query= self::find()->select("subjectid")->where(['status'=>1]);
        if($subject_type){
            $query->andWhere(['subject_type'=>$subject_type]);
        }
        return $query->orderBy("subjectid desc")->all();

    }
    /**
     * 获取详情
     */
    public static function getDetail($subjectid){
        $rediskey="video_subject_".$subjectid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail=$redis->hgetall($rediskey);
        if (empty($detail)) {
           $detail=self::find()->where(['subjectid'=>$subjectid])->asArray()->one();
           if($detail){
                //获取专题下所有课程id
                $detail['courseids']=VideoSubjectItemService::getCourseIdsDb($subjectid);
                $detail['share_url']=Yii::$app->params['sharehost']."/videosubject?subjectid=".$detail['subjectid'];
                $redis->hmset($rediskey,$detail);
                $redis->expire($rediskey,3600*24*3);
           }
        }

        return $detail;
    }
    /**
     * 获取列表详情
     * @param  [type] $subjectids [description]
     * @return [type]             [description]
     */
    public static function getVideoSubjectListInfo($subjectids){
        $ret=[];
        foreach ($subjectids as $key => $value) {
           $ret[]=self::getDetail($value);
        }
        return $ret;
    }

    /**
     * 缓存获取一招最新加入的课程
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getNewCourseidRedis($lastid=NULL,$rn=50){
        $redis = Yii::$app->cache;
        $rediskey="new_videosubject_course";
        // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getNewCourseidDb();
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['courseid'].',';
                $ret = $redis->rpush($rediskey, $value['courseid'],true);
            }
            $redis->expire($rediskey,3600*1);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        //分页数据获取
        if(empty($lastid)){
            $idx=0;
            $ids_data=$redis->lrange($rediskey,0, $rn-1);
        }else{
            $idx = array_search($lastid, $list_arr);
            $ids_data = array_slice($list_arr,$idx+1,$rn);
            //$ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
        }
        return $ids_data;
    }

    /**
     * 数据库获取最新加入一招的课程
     * @return [type] [description]
     */
    public static function getNewCourseidDb(){
         $courseids=VideoSubjectItemService::find()->select("courseid")->orderBy("ctime desc")->asArray()->all();
         return $courseids;
    }
    /**
     * 获取一招内课程买的最多的
     * @return [type] [description]
     */
    public static function getHotCourseidRedis($lastid=NULL,$rn=50){
         $redis = Yii::$app->cache;
        $rediskey="hot_videosubject_course";
        // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getHotCourseidDb();
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['courseid'].',';
                $ret = $redis->rpush($rediskey, $value['courseid'],true);
            }
            $redis->expire($rediskey,3600*1);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        //分页数据获取
        if(empty($lastid)){
            $idx=0;
            $ids_data=$redis->lrange($rediskey,0, $rn-1);
        }else{
            $idx = array_search($lastid, $list_arr);
            $ids_data = array_slice($list_arr,$idx+1,$rn);
            //$ids_data=$redis->lrange($rediskey,$idx+1, $idx+$rn);
        }
        return $ids_data;
    }
    /**
     * 获取订单购买最多的一招课程（最近一个月订单已支付的）
     * @return [type] [description]
     */
    public static function getHotCourseidDb(){
        $sql="select a.`courseid`,COUNT(*) as cn  from `myb_video_subject_item` as a left JOIN  `myb_orderinfo` as b on a.`courseid` =b.`mark` and b.`status` =1 and b.`paytime`>unix_timestamp(date_sub(now(),interval 1 month)) GROUP BY a.`courseid` ORDER BY cn desc";
        $connection = Yii::$app->db; //连接
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
}
