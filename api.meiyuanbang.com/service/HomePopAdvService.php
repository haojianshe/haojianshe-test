<?php
namespace api\service;

use Yii;
use common\models\myb\HomePopAdv;

/**
 * 
 * @author ihziluoh
 * 
 * 首页硬广弹窗
 */
class HomePopAdvService extends HomePopAdv {
  
    /**
     * 根据身份 地区获取弹出广告
     */
    public static function getPopAdv($provinceid,$professionid){
        $rediskey="popadv_".$provinceid.'_'.$professionid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $detail=$redis->get($rediskey);
        if(empty($detail)) {
           $detail=self::find()->where("FIND_IN_SET($provinceid,provinceid)")->andWhere("FIND_IN_SET($professionid,professionid)")->andWhere(['<=','btime',time()])->andWhere(['>=','etime',time()])->andWhere(['status'=>0])->asArray()->all();
           if($detail){
                $redis->set($rediskey,json_encode($detail,true));
                $redis->expire($rediskey,3600*1);
           }
           return $detail;
        }
        return json_decode($detail,true);
    }
}
