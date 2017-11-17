<?php

namespace mis\service;

use Yii;
use common\models\myb\CorrectTalk;
use yii\data\Pagination;

class CorrectTalkService extends CorrectTalk {

    public static function getCorrectTalk($majorcmt_id) {
        return self::find()->select('duration')->where(['talkid' => $majorcmt_id])->one();
    }

}
