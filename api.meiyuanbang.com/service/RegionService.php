<?php

namespace api\service;

use Yii;
use common\models\myb\Region;

/**
 * 获取用户地区信息
 */
class RegionService extends Region {

    /**
     * @describe 获取用户省份下面的市、市下面县
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getUserRegionInfo($region_id, $type = 0) {
        if ($type == 1) {
            $ret =  Region::find()->select(['region_id as area_id', 'region_name as area_name'])->where(['parent_id' => $region_id])->asArray()->all();
            if(!$ret){
               $ret= Region::find()->select(['region_id as area_id', 'region_name as area_name'])->where(['region_id' => $region_id])->asArray()->all();
            }
            return $ret;
        } else {
            return Region::find()->select(['region_id as city_id', 'region_name as city_name'])->where(['parent_id' => $region_id])->asArray()->all();
        }
    }

    /**
     * @describe 获取用户省份下面的市
     * @param  [type] $newsids [description]
     * @return [type]          [description]
     */
    public static function getUserCityInfo($region_id) {
        return Region::find()->select(['region_name'])->where(['region_id' => $region_id])->asArray()->one();
    }

}
