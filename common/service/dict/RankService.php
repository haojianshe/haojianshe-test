<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 用于榜单用户称呼
 */
class RankService extends Object {

    /**
     * 获取用户称谓
     */
    public static function getUserAppellation() {
        return [
            [
                'key' => 1,
                'info' => '青铜画渣',
            ],
            [
                'key' => 2,
                'info' => '白银画徒',
            ],
            [
                'key' => 3,
                'info' => '黄金画师',
            ],
            [
                'key' => 4,
                'info' => '铂金画霸',
            ],
            [
                'key' => 5,
                'info' => '钻石画圣',
            ],
            [
                'key' => 6,
                'info' => '超凡画神',
            ],
            [
                'key' => 7,
                'info' => '美院王者',
            ],
            [
                'key' => 8,
                'info' => '大放异彩',
            ],
            [
                'key' => 9,
                'info' => '技压群雄',
            ],
            [
                'key' => 10,
                'info' => '独孤求败',
            ],
        ];
    }

}
