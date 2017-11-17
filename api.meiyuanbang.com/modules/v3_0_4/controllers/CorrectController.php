<?php

namespace api\modules\v3_0_4\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 榜单逻辑
 * 2017/7/6 暂时不开发
 */
class CorrectController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['*'],
            ],
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['*'],
            ],
        ];
    }

    public function actions() {
        return [
            //分页排行榜接口
            'rankpage' => [
                'class' => 'api\modules\v3_0_4\controllers\correct\RankpageAction',
            ]
        ];
    }

}
