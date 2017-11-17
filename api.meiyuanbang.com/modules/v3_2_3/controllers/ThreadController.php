<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 获取用户批改历史
 */
class ThreadController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['finishe_correct'],
            ],
        ];
    }

    public function actions() {
        return [
            'finishe_correct' => [
                'class' => 'api\modules\v3_2_3\controllers\thread\FinisheCorrectAction',
            ]
        ];
    }

}
