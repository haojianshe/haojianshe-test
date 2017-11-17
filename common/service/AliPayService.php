<?php

namespace common\service;

use Yii;
use yii\base\Object;

/**
 * 支付宝支付封装
 */
class AliPayService extends Object {

    /**
     * 支付宝获取签名
     * @param unknown $params
     * @param unknown $signtype
     * @return unknown
     */
	static function sign($params, $signtype) {
        //引入支付宝支付aopsdk
        require_once __DIR__ . '/../../vendor/alipay-sdk-PHP/AopSdk.php';
        $aop = static::getAop();
        $ret = $aop->generateSign($params,$signtype);
        return $ret;
    }
    
    /**
     * 支付宝通知接口进行签名验证
     */
    static function rsaCheck($arr,$rsaType){
    	//引入支付宝支付aopsdk
    	require_once __DIR__ . '/../../vendor/alipay-sdk-PHP/AopSdk.php';
    	$aop = static::getAop();
    	//第二个参数为证书路径，alipayrsaPublicKey有值的情况下会优先使用值
    	$result = $aop->rsaCheckV1($arr, '', $rsaType);
    	return $result;
    }
    
    /**
     * 创建aopclient
     * @return unknown
     * 说明：本地生成一对公钥和私钥，私钥保存到本地，公钥提供给支付宝，用于在访问支付宝接口时，支付宝进行验证
     *      支付宝公钥与本地的公钥私钥不相同，为支付宝提供，公钥保存到本地，用于验证支付宝通知的签名
     */
    private static function getAop(){
    	//创建aop客户端
    	$aop = new \AopClient ();
    	$aop->appId = 2016040801277575;
    	//签名时用到的私钥
    	$aop->rsaPrivateKey ='MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCJEI3D0tjUuhiXS84u3JGe95N6jAaKGj7XxTwvIxVAIy6AnNwlQC/XbVdNCO3GOxg60BqNm3h1inlUhVV8fWJtHZxKUHUnnPTe605vZqzGJj3oBlOVJsV9RHPxLhs5rjvJPx10Fqsb1eAFZySM5xTWvaaQVJlAyZUsrnLWV6gBLXUiPS/U/uv8D15mTm/qCx3itmXCw6onCAHnmfwiGCzbGOi4bxH85w97zVgeloyJ4onaB3aj7/k77O12ZfQX5RTlSJ/M8VrnsU1xOpdc7fywmFQaR73X+4gW6iDbzzFhVbkXwT+igAhthNd3w0cpzZwpSHCHTbAPDwRe7IX3ZXqTAgMBAAECggEAXyCzE38J6JKEMLV3E0UYeDkiDUKT41OV3BFS1PBHdm00gCTeEah7y8tidN/GjvdCuMboNvH5Z7LExKmJjE5Feq+7YkR3GxgvR2wO0vhy3095VcjWR8VR+cABrBkw4haP/fulCIYXGcmVoopUbsqOxv93U+KzPqjptMoaf7L0smN7onFjsOxwwmzt5WWjpWbc3L9w3ZuH0fjt6+FHAYxf8J/GQwje69qThPP9DXy/sUg4YcVR6awvIOsWfHJ1NhDOU4Yx4dPkY+UvGs7z9TfzAyA6Epe5wQcrSQ7Kw+Ml++QotsYLLs1Nbta/wmAGu2mowG+E3iQLpP96U12p2IAOKQKBgQDuqj63W8CYTvLpWwUcLhhTcUC06b2EC6s3v2rt2zOmI/24v67bH/oOavyM8PBjfJYUfMUwwckcYSsJGGTrYgpPgY9sryx+np8qLdPcWwK3c5XY4k/sLc9J0wscLd6TgSqx+x0G7kmn7gA2AP0vM4ZZ5aZKlDfOUB0zXoZoF7I/TwKBgQCTBSTtcu74eiY4ajtNIcyyjEVaJQUPaTmL2oHru1mQAHjGM5IaO9ZjSVwMGlb4XKmuQpagAh6eVHd02iKL4nJqdUCfs5rfZ5haqAcv3v8TLL+WtTm07DnlQk/MOAdsSm2g+3+K5kRj0Wb0kGZ8fEAh8NRRCotfT7l2+poUuK4ffQKBgQCEOsKgqC8es68V5x3/rPJBWN/23AHqJOTp8B34RphpFku+jkT8lszeLBxcoiktlZs5Ip/GEbexB4zmbyOK3jjEMA4cszk9tfiNT/KvhaUNqvZ8wXZybjcIuerP5AILS2cyUOSWMHOSnOR1Bzs/aZaoO/pUG6aHog9rpJVnGqnhNQKBgDgOUhInLG6ZRFFmT84NARti2F6SludVW5ezbK4Q9Qku5N3Nc/uErS2WHv6OXHK+AgRrZzsDE+GSmyl2so1bffyRUF6UAI0F1tVaE8Nw5xjMixBPcP1GxJxhOEPJaSslBb/HpM21FoZopTTCihEU3u8SACIWPyXMJA71ZiJYeCuRAoGAYumPRj6LprZw/K72GXACPQsK7egXCAS3SqD/4D4jID6l29Xz3S1sC7wZsDp/QOWUTCWE5ocS1/gmKiN8MbccjEufyCQWL13bMUGiWD20ARWPw4we/Z42pqf1X3OCt05OIA0U1Ovl8x2ImqXb6OFweJ/QXzynrUREdfIdl6MmSdY=';
    	//通知接口验证签名时用到的公钥
    	$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAonmlTsfwT31Tf054q55fyD0wIqnzdvjE6WvMnim6Zf43SXJFfpyfZOgItjlpDso2YRC+JHerByyhk3o6w9PVKNKyWTaE4tow0v+48sMGbKa7o24dvIzb0byYpsTIBidqHI+SS+qpBB0pNjkZilPRTmwyEF5FcA5kQO6QoFPmnlH4r+KHVzxFMmA/gtxki3nVGEvmn4Vyt1wi816H002fKakT7mJl4P8VWeNyI9YDf/GyJWhsu1OZ1FNfaMHc9oP+KB7RRXzgJwlqKVvuYcc9g6i4C1HHz3iL7h2zl3keDoWVvzpNR/sDXN0EALibwsPvd/y5BzOh3RzR9rWs8IBERwIDAQAB';
    	return $aop;
    }
}
