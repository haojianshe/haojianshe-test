<?php

namespace common\service\yj;

use Yii;
use yii\base\Object;

/**
 * @字典数据相关逻辑
 * @包括:1，课程类型
 * @author Jianshe Hao
 */
class DictDataService extends Object {

    /**
     * @desc Other courses detail
     * @key 单节课时长（分钟）
     * @courseName 课程类型名称
     * @coursePrice 课程单格 
     * @courseCount 课程总课时
     * @CourseMaterialPricce 课程总材料费
     * @courseSum 课程总价格
     */
    static function getCoursePriceList() {
        return [
            60 => [
                ['courseid' => 1, 'courseName' => '季卡', 'coursePrice' => 70, 'courseCount' => 12, 'CourseMaterialPricce' => 150, 'courseSum' => 990],
                ['courseid' => 2, 'courseName' => '半年卡', 'coursePrice' => 65, 'courseCount' => 24, 'CourseMaterialPricce' => 300, 'courseSum' => 1860],
                ['courseid' => 3, 'courseName' => '年卡', 'coursePrice' => 60, 'courseCount' => 48, 'CourseMaterialPricce' => 500, 'courseSum' => 3380],
            ],
            80 => [
                ['courseid' => 1, 'courseName' => '季卡', 'coursePrice' => 90, 'courseCount' => 12, 'CourseMaterialPricce' => 150, 'courseSum' => 1230],
                ['courseid' => 2, 'courseName' => '半年卡', 'coursePrice' => 85, 'courseCount' => 24, 'CourseMaterialPricce' => 300, 'courseSum' => 2340],
                ['courseid' => 3, 'courseName' => '年卡', 'coursePrice' => 80, 'courseCount' => 48, 'CourseMaterialPricce' => 500, 'courseSum' => 4340],
            ],
            45 => [
                ['courseid' => 1, 'courseName' => '一期', 'coursePrice' => 60, 'courseCount' => 10, 'CourseMaterialPricce' => 100, 'courseSum' => 700],
                ['courseid' => 2, 'courseName' => '二期', 'coursePrice' => 50, 'courseCount' => 20, 'CourseMaterialPricce' => 100, 'courseSum' => 1100],
                ['courseid' => 3, 'courseName' => '三期', 'coursePrice' => 45, 'courseCount' => 30, 'CourseMaterialPricce' => 200, 'courseSum' => 1550],
            ],
        ];
    }

}
