<?php

namespace mis\service;

use Yii;
use yii\data\Pagination;
use mis\models\Resource;

/**
 * 图片处理servicef
 */
class ResourceService extends Resource {

    public static function getPicData($val) {
        return self::find()->select('img')->where(['rid' => $val])->one();
    }

}
