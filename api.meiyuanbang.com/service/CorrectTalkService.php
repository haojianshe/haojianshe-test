<?php
namespace api\service;
use common\models\myb\CorrectTalk;
use Yii;
use common\redis\Cache;
/**
* 
*/
class CorrectTalkService extends CorrectTalk
{
    /**
     * 获取单个语音详情
     * @param  [type] $talkid [description]
     * @return [type]         [description]
     */
    public static function getCorrectTalkDetail($talkid){
        $correcttalk_detail_redis='correct_talk_detail_';
        $rediskey=$correcttalk_detail_redis.$talkid;
        $redis = Yii::$app->cache;
        //$redis->delete($rediskey);
        $correcttalk_detail=$redis->hgetall($rediskey);
        if (empty($correcttalk_detail)) {
           $data=CorrectTalk::findOne(['talkid'=>$talkid])->attributes;
           $redis->hmset($rediskey,$data);
           $data['location']=json_decode($data['location']);
            return $data; 
        }else{
            $correcttalk_detail['location']=json_decode($correcttalk_detail['location']);
            return $correcttalk_detail;
        }
    }

}