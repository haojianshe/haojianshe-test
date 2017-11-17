<?php

namespace api\modules\v3_0_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 画室相关接口
 */
class StudioController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => [''],
            ],
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
            /* 'only' => [''], */
            ],
        ];
    }

    public function actions() {
        return [
            //得到信息
            'get_studio_data' => [
                'class' => 'api\modules\v3_0_1\controllers\studio\GetStudioDataAction',
            ],
            'get_studio_adv' => [
                'class' => 'api\modules\v3_0_1\controllers\studio\GetStudioAdvAction',
            ],
            'get_studio_menu' => [
                'class' => 'api\modules\v3_0_1\controllers\studio\GetStudioMenuAction',
            ],
            'get_class_type' => [#GetClassTypeCAction
                'class' => 'api\modules\v3_0_1\controllers\studio\GetClassTypeCAction',
            ],
            'set_enroll' => [#SetEnrollAction
                'class' => 'api\modules\v3_0_1\controllers\studio\SetEnrollAction',
            ],
            'get_studio_live' => [#GetStudioLiveAction
                'class' => 'api\modules\v3_0_1\controllers\studio\GetStudioLiveAction',
            ],
            'get_order' => [#GetStudioLiveAction
                'class' => 'api\modules\v3_0_1\controllers\studio\GetOrderAction',
            ],
        ];
    }

}
