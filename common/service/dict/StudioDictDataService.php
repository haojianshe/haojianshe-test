<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 用于画室导航
 */
class StudioDictDataService extends Object {

    //*******************************************************tweet type begin
    /**
     * @return multitype:number string
     */
    static function getBookMainType() {
        $ret = [
            '1' => "热门班型",
            '2' => "画室简介",
            '3' => "作品展示",
            '4' => "视频课程",
            '5' => "师资力量",
            '6' => "往期成绩",
            '7' => "教学优势",
            '8' => "学员风采",
            '9' => "学习生活",
            '10' => "新闻资讯",
            '11' => "优惠政策"
        ];
        return $ret;
    }

   
}
