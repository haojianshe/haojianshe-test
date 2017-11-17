<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 老师收支列表
 */
class TeacherController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['reward_list'],
            ]
        ];
    }

    public function actions() {
        return [
            //收支列表明细
            'reward_list' => [
                'class' => 'api\modules\v3_2_3\controllers\teacher\RewardListAction',
            ]
        ];
    }

}
