<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 订单相关
 */
class OrderController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => [],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['coupon_list','get_coupon_price','submit_coupon']
            ],
        ];
    }

    public function actions() {
        return [
            //优惠券列表
            'coupon_list' => [ 
                'class' => 'api\modules\v3_1_1\controllers\order\CouponListAction',
            ],
            //获取订单优惠价格
            'get_coupon_price' => [ 
                'class' => 'api\modules\v3_1_1\controllers\order\GetCouponPriceAction',
            ],
            //提交优惠券更改订单
            'submit_coupon' => [ 
                'class' => 'api\modules\v3_1_1\controllers\order\SubmitCouponAction',
            ],
        ];
    }

}
