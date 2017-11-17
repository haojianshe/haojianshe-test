<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_coupon".
 *
 * @property integer $couponid
 * @property string $coupon_name
 * @property integer $coupon_type
 * @property string $mark
 * @property string $min_price
 * @property string $max_price
 * @property integer $ctime
 * @property integer $btime
 * @property integer $etime
 * @property integer $status
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_name', 'min_price', 'max_price', 'ctime', 'btime', 'etime'], 'required'],
            [['coupon_type', 'ctime', 'btime', 'etime', 'status'], 'integer'],
            [['min_price', 'max_price'], 'number'],
            [['coupon_name', 'mark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'couponid' => 'Couponid',
            'coupon_name' => 'Coupon Name',
            'coupon_type' => 'Coupon Type',
            'mark' => 'Mark',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'ctime' => 'Ctime',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'status' => 'Status',
        ];
    }
}
