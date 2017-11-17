<?php

namespace common\service\dict;

use Yii;
use yii\base\Object;

/**
 * 打赏奖品列表
 */
class CorrectGiftService extends Object {

    //阿里云img地址
    const URL = 'https://img.meiyuanbang.com/activity/gift/img/';

    /**
     * 获取全部打赏奖品
     * @return array 
     */
    public static function getGiftData() {
        return [
            [
                'gift_id' => 1,
                'gift_name' => '么么哒',
                'gift_img' => self::URL . 'daliwu_memeda3x.png',
                'gift_img_min' => self::URL . 'miniliwu_memeda.png',
                'gift_price' => 1,
                'productid' => 'myb_in_app_purchase_1_gift'
            ],
            [
                'gift_id' => 2,
                'gift_name' => '喉宝',
                'gift_img' => self::URL . 'daliwu_houbao3x.png',
                'gift_img_min' => self::URL . 'miniliwu_houbao.png',
                'gift_price' => 3,
                'productid' => 'myb_in_app_purchase_3_gift'
            ],
            [
                'gift_id' => 3,
                'gift_name' => '鲜花',
                'gift_img' => self::URL . 'daliwu_xianhua3x.png',
                'gift_img_min' => self::URL . 'miniliwu_xianhua.png',
                'gift_price' => 6,
                'productid' => 'myb_in_app_purchase_6_gift'
            ],
            [
                'gift_id' => 4,
                'gift_name' => '钻石',
                'gift_img' => self::URL . 'daliwu_zuanshi3x.png',
                'gift_img_min' => self::URL . 'miniliwu_zuanshi.png',
                'gift_price' => 8,
                'productid' => 'myb_in_app_purchase_8_gift'
            ],
            [
                'gift_id' => 5,
                'gift_name' => '皇冠',
                'gift_img' => self::URL . 'daliwu_huangguan3x.png',
                'gift_img_min' => self::URL . 'miniliwu_huangguan.png',
                'gift_price' => 18,
                'productid' => 'myb_in_app_purchase_18_gift'
            ],
            [
                'gift_id' => 6,
                'gift_name' => '跑车',
                'gift_img' => self::URL . 'daliwu_paoche3x.png',
                'gift_img_min' => self::URL . 'miniliwu_paoche.png',
                'gift_price' => 28,
                'productid' => 'myb_in_app_purchase_28_gift'
            ],
        ];
    }

    /**
     * 根据单个奖品
     * @return multitype:string
     */
    public static function getGiftOneList($subjectid) {
        $array = [];
        $resonlist = static::getGiftData();
        foreach ($resonlist as $k => &$v) {
            if ($v['gift_id'] == $subjectid) {
                $array[] = $v;
            }
        }
        return $array;
    }

}
