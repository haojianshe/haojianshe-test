<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 打赏列表
 */
class RewardController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['list']
            ],
        ];
    }

    public function actions() {
        return [
            //获取奖品列表
            'list' => [
                'class' => 'api\modules\v3_2_3\controllers\reward\ListAction',
            ]
        ];
    }

}
