<?php

namespace common\service;

use Yii;
use yii\base\Object;

//引入微信支付api类，对应的为微信公众平台账号（美院帮服务号）所对应的商户号配置
require_once __DIR__ . '/../../vendor/WxpayAPI_php_v3/lib/WxPay.Api.php';
require_once __DIR__ . '/../../vendor/WxpayAPI_php_v3/lib/WxPay.Notify.php';

/**
 * 微信通知处理类，接收在微信中或者浏览器中使用微信支付的通知的处理
 */
class WxBrowserPayCallBack extends \WxPayNotify {
    /**
     * 重写WxPayNotify类的回调的处理函数
     * 在此函数中处理微信支付的业务逻辑
     */
    public function NotifyProcess($data, &$msg)
    {
    	return WxCommonProcess::CommonProcess($data, $msg);    
    }
}
