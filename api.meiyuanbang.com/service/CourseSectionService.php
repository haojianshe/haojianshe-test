<?php
namespace api\service;
use common\models\myb\CourseSection;
use Yii;
use common\redis\Cache;
use api\service\CourseSectionVideoService;
/**
* 课程章节相关方法
*/
class CourseSectionService extends CourseSection
{
    /**
     * 获取详情
     */
    public static function getDetail($sectionid){
        $rediskey="course_section_detail_".$sectionid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail=$redis->hgetall($rediskey);
        if (empty($detail)) {
           $detail=self::find()->where(['sectionid'=>$sectionid])->asArray()->one();
           if($detail){
                $redis->hmset($rediskey,$detail);
                $redis->expire($rediskey,3600*24*3);
           }
        }
        return $detail;
    }
    public static function getCourseSectionList($courseid){
        $redis = Yii::$app->cache;
        $rediskey="course_section_".$courseid;
       // $redis->delete($rediskey);
        $list_arr=$redis->lrange($rediskey,0, -1);
        //判断缓存是否有内容 若无则重新建立缓存
        if(empty($list_arr)){
            $model=self::getCourseSectionListDb($courseid);
            $ids='';
            foreach ($model as $key => $value) {
                $ids.=$value['sectionid'].',';
                $ret = $redis->rpush($rediskey, $value['sectionid'],true);
            }
            $redis->expire($rediskey,3600*24*3);
            $ids=substr($ids, 0,strlen($ids)-1);
            $list_arr=explode(',', $ids);
        }
        return $list_arr;
    }
    public static function getCourseSectionListDb($courseid){
        return self::find()->select('sectionid')->where(['courseid'=>$courseid])->andWhere(['status'=>1])->orderBy("section_num asc")->all();
    }
    public static function getCourseSectionListDetail($sectionids,$uid){
        $ret_list=[];
        foreach ($sectionids as $key => $value) {
           $section_item=self::getDetail($value);
           $section_item['videos']=CourseSectionVideoService::getVideosInfoBySectionid($section_item['sectionid'],$uid);
           $ret_list[]=$section_item;
        }
        return $ret_list;
    }
}