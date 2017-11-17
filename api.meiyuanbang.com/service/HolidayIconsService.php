<?php
namespace api\service;

use Yii;
use common\models\myb\HolidayIcons;
/**
 * @author ihziluoh
 * 节日图标
 */
class HolidayIconsService extends HolidayIcons
{
	/**
	 * 获取详情
	 */
	public static function getIconsDetail(){
	    $rediskey="holidayicons";
	    $redis = Yii::$app->cache;
	    $detail=$redis->hgetall($rediskey);
	    if (empty($detail)) {
	       $detail=HolidayIcons::find()->where(['status'=>3])->asArray()->one();
	       if($detail){
	            $redis->hmset($rediskey,$detail);
	            $redis->expire($rediskey,1800);
	       }
	    }
	    return $detail;
	}
}