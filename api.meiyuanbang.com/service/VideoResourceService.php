<?php
namespace api\service;
use common\models\myb\VideoResource;
use Yii;
use common\redis\Cache;

/**
* 基础视频详情
*/
class VideoResourceService extends VideoResource
{
   /**
    * 获取详情
    */
   public static function getDetail($videoid){
       $rediskey="video_detail_".$videoid;
       $redis = Yii::$app->cache;
       // $redis->delete($rediskey);
       $detail=$redis->hgetall($rediskey);
       if (empty($detail)) {
          $detail=self::find()->where(['videoid'=>$videoid])->asArray()->one();
          if($detail){
               $redis->hmset($rediskey,$detail);
               $redis->expire($rediskey,3600*24*3);
          }
       }
       $detail['video_size']=intval($detail['video_size']/1024/1024) ;
       return $detail;
   }

}