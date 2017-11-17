<?php

namespace api\modules\v3_0_4\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 相关课程
 */
class CourseController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['related']
            ],
        ];
    }

    public function actions() {
        return [
            //得到和该课程相关的课程和所讲课程的老师的相关课程
            'related' => [ # CourseService::getCourseidByCorrectid 
                'class' => 'api\modules\v3_0_4\controllers\course\RelatedAction',
            ],
        ];
    }

}
