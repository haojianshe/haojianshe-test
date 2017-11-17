<?php
namespace common\service;

use Yii;
use yii\base\Object;

//引入阿里云core sdk，主要为引入 autoload方法
require_once __DIR__ . '/../../vendor/aliyun/aliyun-php-sdk-core/Config.php';
//引入mts sdk命名空间
use Mts\Request\V20140618 as Mts;

/**
 * 阿里云媒体转码服务封装类
 */
class AliMtsService extends Object
{    
	/**
	 * 获取aliyun client实例，统一修改key 和 id
	 */
	static private function getClient($regionid){		
		$ret = \DefaultProfile::getProfile($regionid, "0uMRHb0hY555pkxQ", "oKRwSn6WFVQL8U6pupO2wswCiA38La");
		return $ret;
	}
	
	/**
	 * 获取媒体文件信息
	 * 目前只为直播转录播后生成的mp4文件使用,regionid固定为上海
	 * @param unknown $bucket
	 * @param unknown $location
	 * @param unknown $object
	 * @return Ambigous <mixed, unknown>|boolean
	 */
	static function getMediaInfo($bucket,$location,$object){
		//直播目前固定为上海
		$regionid = 'cn-shanghai';
		//建立client对象
		$iClientProfile = static::getClient($regionid);
		$client = new \DefaultAcsClient($iClientProfile);
				
		//建立mediainfo对象
		$requestMts = new Mts\SubmitMediaInfoJobRequest();
		$requestMts->setMethod("GET");
		$input = "{'Bucket':'". $bucket ."','Location':'" . $location . "','Object':'" . $object . "'}";
		//设置input 参数
		$requestMts->setInput($input);
		$requestMts = $client->getAcsResponse($requestMts);
		if($requestMts){
			return $requestMts;
		}
		return false;
	}   
}