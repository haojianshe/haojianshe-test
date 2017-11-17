<?php
namespace api\service;
use Yii;
use common\models\myb\PosidHome;
use common\redis\Cache;

/**
 * 广告方法
 */

class PosidHomeService extends PosidHome
{
    /**
     * 得到广告
     * @param  [type] $lastid [description]
     * @param  [type] $rn     [description]
     * @return [type]         [description]
     */
    public static function getPosidHomeList($channelid){
        $rediskey="postidhome_list".$channelid;
        $redis=Yii::$app->cache;
        //$redis->delete($rediskey);
        $mlist=$redis->get($rediskey);
        if(empty($mlist)){
            //数据库获取
            $mlist=json_encode(self::getPosidHomeListDB($channelid));
            $redis->set($rediskey,$mlist);
            $redis->expire($rediskey,3600*24*3);
        }
        if(empty($mlist)){
            return array();
        }else{
            return json_decode($mlist); 
        }
    }

    /**
     * 得到数据库中对应的广告位
     * @return [type] [description]
     */
    public static function getPosidHomeListDB($channelid){
        //数据库中获取专题id
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('select * from '.parent::tableName().' where status=0 and channelid='.$channelid.'  order by listorder asc');
        $data = $command->queryAll();
        return $data;
    }
}
