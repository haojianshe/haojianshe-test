<?php

namespace api\modules\v2_3_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 
 */
class DkController extends ApiBaseController {

    public function behaviors() {
        return [
            //token检查
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['get_prize_user', 'set_user_prize', 'submit','is_teacher',"share","hadsubmit"]
            ],
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['submit'],
            ],
        ];
    }

    public function actions() {
        return [
            //获取抽奖接口
            'get_prize_user' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\GetprizeuserAction',
            ],
            //提交中奖用户信息接口
            'set_user_prize' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\SetuserprizeAction',
            ],
            //记录用户每天分享接口
            'share' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\ShareAction',
            ],
            //点赞
            'zan' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\ZanAction',
            ],
            //求批改
            'submit' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\SubmitAction',
            ],
            //大咖批改列表
            'dkcorrect' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\DkCorrectAction',
            ],
            //大咖求批改
            'dk_update_teacher' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\DkupdateteacherAction',
            ],
            //判断是否是活动老师
            'is_teacher' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\IsTeacherAction',
            ],
            //判断是否已经参加了活动
            'hadsubmit' => [
                'class' => 'api\modules\v2_3_3\controllers\dk\HadSubmitAction',
            ]
        ];
    }

}
