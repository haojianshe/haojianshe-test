<?php

namespace api\modules\v3_0_4\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 获取帖子分类信息
 */
class OrderController extends ApiBaseController {

    public function behaviors() {
        return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => ['applepay_receipt'],
          	  //'only' => [''],
          ],
      ];
    }

    public function actions() {
        return [
            //苹果内购，服务器端进行二次验证接口，并修改订单支付状态
            'applepay_receipt' => [
                'class' => 'api\modules\v3_0_4\controllers\order\ApplePayReceiptAction',
            ]
        ];
    }

}
