<?php
namespace api\service;

use Yii;
use common\models\myb\Lk;
use common\service\DictdataService;
use common\models\myb\News;
use common\models\myb\NewsData;
/**
 * 
 * @author ihziluoh
 * 
 * 联考
 */
class LkService extends Lk 
{
    /**
     * 获取联考模拟考详情
     */
    public static function getLkDetail($lkid){
        $rediskey="lk_".$lkid;
        $redis = Yii::$app->cache;
         //$redis->delete($rediskey);
        $detail=$redis->hgetall($rediskey);
        if (empty($detail)) {
                $detail=self::getLkDetailDb($lkid);
                if($detail){
                    $redis->hmset($rediskey,$detail);
                    $redis->expire($rediskey,3600*24*3);
                }
        }
        if($detail){
            //省份名称
            $detail['provincename'] = DictdataService::getUserProvinceById($detail['provinceid']);
        }        
        return $detail;
        
    }
    /**
     * 获取联考城市列表
     * @return [type] [description]
     */
    public static function getProvinceList(){
        $ret_data=[];
        $pids=self::find()->select("provinceid,lkid")->where(['<','btime',time()])->andWhere(['status'=>1])->groupBy("provinceid")->asArray()->all();
        foreach ($pids as $key => $value) {
           $ret['provincename'] = DictdataService::getUserProvinceById($value['provinceid']);
           $ret['url'] = Yii::$app->params['sharehost'].'/mactivity/lk/index?lkid='.$value['lkid'];
           $ret['provinceid'] = $value['provinceid'];
           $ret_data[]=$ret;
        }

        return $ret_data;
    }
    /**
     * 清除联考缓存
     * @param  [type] $lkid [description]
     * @return [type]       [description]
     */
    public static function clearLkRedis($lkid){
        $rediskey="lk_".$lkid;
        $redis = Yii::$app->cache;
        $redis->delete($rediskey);
    }
     /**
     * 数据库获取联考模拟考详情
     * @param  [type] $newsid [description]
     * @return [type]         [description]
     */
    public static function getLkDetailDb($lkid) {
        $ret=self::find()->select("*")->where(['lkid'=>$lkid])->asArray()->one();
        $newsid=$ret['newsid'];
        $news = News::find()->select("*")->where(['newsid' => $newsid])->asArray()->one();
        $newsdata = NewsData::find()->select("hits,cmtcount,supportcount,copyfrom,reserve1,reserve2,reserve3")->where(["newsid" => $newsid])->asArray()->one();
        if (empty($ret) || empty($news) || empty($newsdata)) {
            if($ret){
                $ret['lk_title']=$ret['title'];
                return $ret;
            }else{
                return [];
            }
        }
        $return_arr = array_merge($ret, $news, $newsdata);
        $return_arr['lk_title']=$ret['title'];
        return $return_arr;
    }
}