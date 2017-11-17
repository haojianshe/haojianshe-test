<?php
namespace api\service;
use common\models\myb\LiveSign;
use Yii;
use common\redis\Cache;
/**
* 直播报名相关方法
*/
class LiveSignService extends LiveSign
{
	/**
	 * 获取用户报名状态
	 * @param  [type] $liveid [description]
	 * @param  [type] $uid    [description]
	 * @return [type]         [description]
	 */
	public static function getUserSignStatus($liveid,$uid){
		$findrec=self::find()->where(['liveid'=>$liveid])->andWhere(['uid'=>$uid])->one();
		if($findrec){
			//已报名
			return '1';
		}else{
			//未报名
			return '0';
		}
	}
	/**
     * 获取直播报名数
     */
    public static function getLiveSign($liveId) {
        $redis = Yii::$app->cache;
        $rediskey = "live_sign_" . $liveId;
        if (!$redis->get($rediskey)) {
            return LiveSign::find()->where(['liveid' => $liveId])->count();
        } else {
            return $redis->get($rediskey);
        }
    }

}