<?php

namespace api\modules\v2_3_7\controllers;

use api\components\ApiBaseController;

/**
 * 用户接口
 */
class UserController extends ApiBaseController {

    public function behaviors() {
        return [
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['get_info']
            ]
        ];
    }

    public function actions() {
        return [
            //获取用户信息
            'get_info' => [
                'class' => 'api\modules\v2_3_7\controllers\user\GetInfoAction',
            ]
        ];
    }

}
