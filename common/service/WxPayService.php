<?php

namespace common\service;

use Yii;
use yii\base\Object;
//引入微信支付api类
require_once __DIR__ . '/../../vendor/WxpayAPI_php/lib/WxPay.Api.php';

/**
 * 微信支付封装
 */
class WxPayService extends Object {

    /**
     * 调用统一下单接口
     * @param unknown $orderinfo
     * @return unknown
     */
	static function unified($orderinfo) {
        //引入支付宝支付aopsdk 
        $input = new \WxPayUnifiedOrder();
        //商品描述
        $input->SetBody($orderinfo['ordertitle']);
        //附加数据
        $input->SetAttach("");
        $input->SetOut_trade_no($orderinfo['orderid']);
        $input->SetTotal_fee($orderinfo['fee']);
        //交易起始时间
        $input->SetTime_start(date("YmdHis"));
        //交易结束时间,腾讯要求必须大于5分钟
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //商品标记
        $input->SetGoods_tag("MYB");
        //接收支付通知的url
        $input->SetNotify_url(Yii::$app->params['wxpay_notifyurl']);
        $input->SetTrade_type("APP");
        $order = \WxPayApi::unifiedOrder($input);
        return $order;
    }
    
	/**
     * 第一步调用微信统一下单接口成功后，根据返回结果组织发起支付时用到的参数和签名
     * @param unknown $order
     * @throws WxPayException
     * @return string
     */
    static function getWXAppPayParams($UnifiedOrderResult){
    	if(!array_key_exists("appid", $UnifiedOrderResult)
    			|| !array_key_exists("prepay_id", $UnifiedOrderResult)
    			|| $UnifiedOrderResult['prepay_id'] == "")
    	{
    		throw new WxPayException("参数错误");
    	}
    	$appapi = new \WxPayAppPay();
    	$appapi->SetAppid($UnifiedOrderResult["appid"]);
    	$appapi->SetPartnerid($UnifiedOrderResult["mch_id"]);
    	$appapi->SetPrepayid($UnifiedOrderResult['prepay_id']);
    	$appapi->SetPackage('Sign=WXPay');    	
    	$timeStamp = time();
    	$appapi->SetTimeStamp("$timeStamp");    	   	
    	$appapi->SetNonceStr(\WxPayApi::getNonceStr());
    	$appapi->SetSign($appapi->MakeSign());
    	//返回签名值
    	$parameters = $appapi->GetValues();
    	return $parameters;
    }
}