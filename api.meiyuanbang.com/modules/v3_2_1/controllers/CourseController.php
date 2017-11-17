<?php

namespace api\modules\v3_2_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 课程分享抽奖相关接口
 */
class CourseController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['sharelotto_add','show_sharelotto'],
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                /*'only' => ['']*/
            ],
        ];
    }

    public function actions() {
        return [
           
            //增加分享记录
            'sharelotto_add' => [
                'class' => 'api\modules\v3_2_1\controllers\coursesharelotto\SharelottoAddAction',
            ],
             //是否显示抽奖活动
            'show_sharelotto' => [
                'class' => 'api\modules\v3_2_1\controllers\coursesharelotto\ShowSharelottoAction',
            ],
        ];
    }

}
