<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_user_coupon".
 *
 * @property integer $usercouponid
 * @property integer $uid
 * @property integer $couponid
 * @property integer $coupongrantid
 * @property integer $status
 */
class UserCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_user_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'couponid', 'coupongrantid'], 'required'],
            [['uid', 'couponid', 'coupongrantid', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usercouponid' => 'Usercouponid',
            'uid' => 'Uid',
            'couponid' => 'Couponid',
            'coupongrantid' => 'Coupongrantid',
            'status' => 'Status',
        ];
    }
}
