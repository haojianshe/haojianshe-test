<?php

namespace api\modules\v3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 订单相关接口
 */
class OrderController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['create', 'get_info', 'paysign'],
            ],
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            /* 'only' => [''], */
            ],
        ];
    }

    public function actions() {
        return [
            //创建订单
            'create' => [
                'class' => 'api\modules\v3\controllers\order\CreateAction',
            ],
            //获取订单信息
            'get_info' => [
                'class' => 'api\modules\v3\controllers\order\GetInfoAction',
            ],
            //支付宝和微信获取签名
            'paysign' => [
                'class' => 'api\modules\v3\controllers\order\PaySignAction',
            ],
            //支付宝支付通知接口
            'alipay_notify' => [
                'class' => 'api\modules\v3\controllers\order\AlipayNotifyAction',
            ],
            //微信支付通知接口
            'wx_notify' => [
                'class' => 'api\modules\v3\controllers\order\WXNotifyAction',
            ],
            //微信公众号、浏览器支付通知接口
            'wx_browser_notify' => [
                'class' => 'api\modules\v3\controllers\order\WXBrowserNotifyAction',
            ],
        ];
    }

}
