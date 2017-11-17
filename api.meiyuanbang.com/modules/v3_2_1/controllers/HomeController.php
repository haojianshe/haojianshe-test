<?php

namespace api\modules\v3_2_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 首页相关接口
 */
class HomeController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => [''],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',

            ],
        ];
    }

    public function actions() {
        return [
            //获取弹出广告
            'pop_adv' => [
                'class' => 'api\modules\v3_2_1\controllers\home\PopAdvAction',
            ],
        ];
    }

}
