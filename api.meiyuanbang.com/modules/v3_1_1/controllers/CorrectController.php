<?php

namespace api\modules\v3_1_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 */
class CorrectController extends ApiBaseController {

    public function behaviors() {
        return [
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['complete_correct', 'rankpage', 'get_correct_spend'],
            ],
        ];
    }

    public function actions() {
        return [
            //已经完成的批改
            'complete_correct' => [
                'class' => 'api\modules\v3_1_1\controllers\correct\CompleteCorrectAction',
            ],
            //分页排行榜接口
            'rankpage' => [
                'class' => 'api\modules\v3_1_1\controllers\correct\RankpageAction',
            ],
            //获取批改次数
            'get_correct_spend' => [
                'class' => 'api\modules\v3_1_1\controllers\correct\GetCorrectSpendAction',
            ],
        ];
    }

}
