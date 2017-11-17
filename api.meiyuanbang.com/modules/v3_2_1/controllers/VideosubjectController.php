<?php

namespace api\modules\v3_2_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 一招相关接口
 */
class VideosubjectController extends ApiBaseController {

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
                'only' => ['get_new','get_hot','list']
            ],
        ];
    }

    public function actions() {
        return [
            //一招最新列表
            'get_new' => [
                'class' => 'api\modules\v3_2_1\controllers\videosubject\GetNewAction',
            ],
            //一招最热列表
            'get_hot' => [
                'class' => 'api\modules\v3_2_1\controllers\videosubject\GetHotAction',
            ],
            //一招分类列表
            'list' => [
                'class' => 'api\modules\v3_2_1\controllers\videosubject\ListAction',
            ], //一招分类列表
            'catalog' => [
                'class' => 'api\modules\v3_2_1\controllers\videosubject\CatalogAction',
            ],
        ];
    }

}
