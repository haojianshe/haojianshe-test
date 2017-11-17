<?php

namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 直播评论列表接口
 */
class LiveController extends ApiBaseController {

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
            //得到直播评论列表信息
            'get_live_list' => [
                'class' => 'api\modules\v3_0_2\controllers\live\GetLiveListAction',
            ],
            //得到最小的直播列表信息
            'get_refresh_info' => [
                'class' => 'api\modules\v3_0_2\controllers\live\GetRefreshInfoAction',
            ],
             //得到最小的直播列表信息
            'get_live_record' => [
                'class' => 'api\modules\v3_0_2\controllers\live\GetLiveRecordAction',
            ],
        ];
    }

}
