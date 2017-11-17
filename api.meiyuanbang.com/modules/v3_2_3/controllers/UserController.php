<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class UserController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
//                'only' => ['region_info', 'user_school_info'],
            ],
        ];
    }

    public function actions() {
        return [
            //获取用户地区信息
            'region_info' => [
                'class' => 'api\modules\v3_2_3\controllers\user\RegionInfoAction',
            ],
            //获取用户学校信息
            'user_school_info' => [
                'class' => 'api\modules\v3_2_3\controllers\user\UserSchoolInfoAction',
            ]
        ];
    }

}
