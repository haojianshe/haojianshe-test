<?php

namespace api\modules\v3_2_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 批改相关接口
 */
class CorrectController extends ApiBaseController {

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
                'only' => ['']
            ],
        ];
    }

    public function actions() {
        return [
            //是否显示付费批改
            'show_pay_correct' => [
                'class' => 'api\modules\v3_2_3\controllers\correct\ShowPayCorrectAction',
            ],
            //付费批改老师列表
            'pay_correct_teacher' => [
                'class' => 'api\modules\v3_2_3\controllers\correct\PayCorrectTeacherAction',
            ], 
            //付费批改老师推荐
            'pay_correct_teacher_rec' => [
                'class' => 'api\modules\v3_2_3\controllers\correct\PayCorrectTeacherRecAction',
            ],
            
        ];
    }

}
