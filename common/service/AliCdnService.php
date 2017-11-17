<?php
namespace common\service;

use Yii;
use yii\base\Object;

//引入阿里云core sdk，主要为引入 autoload方法
require_once __DIR__ . '/../../vendor/aliyun/aliyun-php-sdk-core/Config.php';
//引入cdn sdk命名空间
use Cdn\Request\V20141111 as Cdn;

/**
 * 阿里云cdn服务封装类
 */
class AliCdnService extends Object
{    
	/**
	 * 获取aliyun client实例，统一修改key 和 id
	 */
	static private function getClient(){		
		$ret = \DefaultProfile::getProfile("cn-beijing", "0uMRHb0hY555pkxQ", "oKRwSn6WFVQL8U6pupO2wswCiA38La");
		return $ret;
	}
	
	/**
	 * 清缓存
	 * @param unknown $objectType 清缓存类型  File | Directory
	 * @param unknown $objectPath
	 */
	static function refresh($objectType,$objectPath){
		//建立client对象
		$iClientProfile = static::getClient();
		$client = new \DefaultAcsClient($iClientProfile);		
		//建立cdn对象
		$requestCdn = new Cdn\RefreshObjectCachesRequest();
		$requestCdn->setMethod("GET");
		//设置object类型
		$requestCdn->setObjectType($objectType);
		//设置object路径
		$requestCdn->setObjectPath($objectPath);
		$responseCdn = $client->getAcsResponse($requestCdn);
		if($responseCdn && $responseCdn->RefreshTaskId ){
			return true;
		}
		return false;
	}   
}