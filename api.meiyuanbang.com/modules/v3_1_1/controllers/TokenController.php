<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * token定时检查接口
 */
class TokenController extends ApiBaseController {
    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['check'],
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
            //检查token有效性，主要用于账号不能在多台终端同时登录功能
            'check' => [  
                'class' => 'api\modules\v3_1_1\controllers\token\CheckAction',
            ],
            //检查token有效性，主要用于账号不能在多台终端同时登录功能(网页端)
            'mcheck' => [  
                'class' => 'api\modules\v3_1_1\controllers\token\MCheckAction',
            ],
        ];
    }

}
