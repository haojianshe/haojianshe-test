<?php

namespace api\modules\v3_0_4\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 获取帖子分类信息
 */
class LiveController extends ApiBaseController {

    public function behaviors() {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['list','get_live_category']
            ],
        ];
    }

    public function actions() {
        return [
            //获取直播课列表
            'list' => [
                'class' => 'api\modules\v3_0_4\controllers\live\ListAction',
            ],
            'get_live_category' => [
                'class' => 'api\modules\v3_0_4\controllers\live\GetLiveCategoryAction',
            ]
        ];
    }

}
