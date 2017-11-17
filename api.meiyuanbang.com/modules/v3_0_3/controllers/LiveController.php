<?php

namespace api\modules\v3_0_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 获取帖子分类信息
 */
class LiveController extends ApiBaseController {

    public function behaviors() {
        return [
        ];
    }

    public function actions() {
        return [
            //获取直播课列表
            'list' => [
                'class' => 'api\modules\v3_0_3\controllers\catalog\GetAction',
            ],
        ];
    }

}
