<?php

namespace mis\service;

use Yii;
use yii\redis\Cache;
use yii\data\Pagination;
use common\models\myb\UserRepeatlogin;

/**
 * 被邀请人APP使用时长
 */
class UserRepeatloginService extends UserRepeatlogin {

    public static function getRepeatlogin($uid) { # /60
        return self::find()->select("sum(`updatetimes`*3) as hours")->where(['uid' => $uid])->asArray()->one();
    }

}
