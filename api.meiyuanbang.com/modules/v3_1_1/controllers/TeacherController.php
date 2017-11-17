<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 相关课程
 */
class TeacherController extends ApiBaseController {
    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['bounty_list'],
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
            //老师佣金记录
            'bounty_list' => [  
                'class' => 'api\modules\v3_1_1\controllers\teacher\BountyListAction',
            ],
        ];
    }

}
