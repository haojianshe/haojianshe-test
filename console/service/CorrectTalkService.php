<?
namespace console\service;

use common\models\myb\CorrectTalk;
use Yii;
use common\redis\Cache;
/**
* 
*/
class CorrectTalkService extends CorrectTalk
{
    /**
     * 清除单个语音信息的缓存
     * @param unknown $talkid
     */
    public static function removeCache($talkid){
        $rediskey = 'correct_talk_detail_' . $talkid;
        $redis = Yii::$app->cache;
        
        $redis->delete($rediskey);
    }

}