<?
namespace console\service;

use common\models\myb\Correct;
use Yii;
use common\redis\Cache;
/**
* 
*/
class CorrectService extends Correct
{   
	//单个批改详情    
    static $correct_detail_redis='correct_detail_';
    
    /**
     * 获取单个批改信息,与api接口service不同，此方法不建立缓存
     * @param  [type] $correctid [description]
     * @return [type]            [description]
     */
    public static function getDetail($correctid){
        $rediskey=  self::$correct_detail_redis . $correctid;
        $redis = Yii::$app->cache;
        
        $ret = $redis->hgetall($rediskey);
        if (empty($ret)) {
            $ret = Correct::findOne(['correctid'=>$correctid])->attributes;
        }
        return $ret;
    }
}

