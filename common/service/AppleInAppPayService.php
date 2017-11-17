<?php

namespace common\service;

use Yii;
use yii\base\Object;

/**
 * 苹果内购支付封装
 */
class AppleInAppPayService extends Object {
	
    /**
     * 调用统一下单接口
     * @param unknown $orderinfo
     * @return unknown
     */
	static function receipt($receipt,$issandbox) {
		if ($issandbox) {  
	        $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';//沙箱地址  
	    } else {  
	        $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';//真实运营地址  
	    }
	    $postData = json_encode(  
	        array('receipt-data' => $receipt)  
	    );  
	    $ch = curl_init($endpoint);  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	    curl_setopt($ch, CURLOPT_POST, true);  
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  //这两行一定要加，不加会报SSL 错误  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	    $response = curl_exec($ch);  
	    $errno    = curl_errno($ch);  
	    curl_close($ch);   
	    return $response;
    }
    
    /**
     * 检查美院帮签名是否正确
     * 签名规则，$receipt+$orderid+$timespan+$mybsignkey
     * @param unknown $sign
     * @param unknown $receipt
     * @param unknown $timespan
     * @param unknown $orderid
     */
    static function checkMybSign($sign,$receipt,$orderid,$timespan){
    	//美院帮的加密字符串
    	$mybsignkey = 'myb1a2s3dftrcvm879lkjhytre345swb';
    	$signstr = $receipt.$orderid.$timespan.$mybsignkey;
    	$checksign = md5($signstr);
    	if($sign==$checksign){
    		return true;
    	}
    	else {
    		return false;
    	}
    }
}