<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 优惠券列表
 */
class CouponController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['list'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['']
            ],
        ];
    }

    public function actions() {
        return [
            //优惠券列表
            'list' => [ 
                'class' => 'api\modules\v3_1_1\controllers\coupon\ListAction',
            ],
        ];
    }

}
