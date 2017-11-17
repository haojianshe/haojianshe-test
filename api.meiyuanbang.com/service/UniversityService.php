<?php

namespace api\service;

use Yii;
use common\models\myb\University;

/**
 * 获取用户大学信息
 */
class UniversityService extends University {

    /**
     * @describe 获取用户大学信息
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getUserUniversity($provinceid, $region_id) {
        return University::find()->select(['universityid as school_id', 'school'])->where(['province_id' => $provinceid,'city_id' => $region_id])->asArray()->all();
    }

}
