<?php
namespace mis\service;

use Yii;
use common\models\myb\TeamInfo;

/**
 * 殿堂老师小组相关逻辑
 *
 */
class TeamInfoService extends TeamInfo 
{
	/**
	 * 清除单个小组的缓存
	 * @param unknown $teamid
	 */
	public static function remove_teaminfo_cache($teamid){
		$redis = Yii::$app->cache;
    	$key = "team_info_" .$teamid;
    	$redis->delete($key);	
	}
}
