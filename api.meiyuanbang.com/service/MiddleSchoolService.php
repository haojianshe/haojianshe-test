<?php

namespace api\service;

use Yii;
use common\models\myb\MiddleSchool;

/**
 * 获取用户中学、高中
 */
class MiddleSchoolService extends MiddleSchool {

    /**
     * @describe 获取用户中学高中信息
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getUserMiddleSchool($provinceid, $region_id, $county_id) {
        return MiddleSchool::find()->select(['schoolid as school_id', 'school'])->where(['province_id' => $provinceid,'city_id' => $region_id,'area_id' => $county_id])->asArray()->all();
    }
 

}
