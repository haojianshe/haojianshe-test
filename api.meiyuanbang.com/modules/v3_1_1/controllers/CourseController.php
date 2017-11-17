<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 老师推荐的相关课程
 */
class CourseController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['recommend_course'],
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
            //得到与改画相关的所以课程列表
            'recommend_course' => [
                'class' => 'api\modules\v3_1_1\controllers\course\RecommendCourseAction',
            ],
        ];
    }

}
