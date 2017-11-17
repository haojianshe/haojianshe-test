<?php

namespace api\modules\v3_2_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 */
class CorrectController extends ApiBaseController {

    public function behaviors() {
        return [
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => [''],
            ],
        ];
    }

    public function actions() {
        return [
            //分页排行榜接口
            'rank_cover' => [
                'class' => 'api\modules\v3_2_1\controllers\correct\RankCoverAction',
            ]
            
        ];
    }

}
