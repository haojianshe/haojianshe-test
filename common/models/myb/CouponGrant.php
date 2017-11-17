<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_coupon_grant".
 *
 * @property integer $coupongrantid
 * @property string $title
 * @property integer $granttype
 * @property string $uids
 * @property string $mobiles
 * @property string $waiting_grant_mobiles
 * @property integer $num
 * @property string $couponid
 * @property string $mis_userid_grant
 * @property string $mis_userid_audit
 * @property integer $status
 * @property string $ctime
 */
class CouponGrant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_coupon_grant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'num', 'couponid', 'mis_userid_grant', 'ctime'], 'required'],
            [['granttype', 'num', 'couponid', 'mis_userid_grant', 'mis_userid_audit', 'status', 'ctime'], 'integer'],
            [['uids', 'mobiles', 'waiting_grant_mobiles'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupongrantid' => 'Coupongrantid',
            'title' => 'Title',
            'granttype' => 'Granttype',
            'uids' => 'Uids',
            'mobiles' => 'Mobiles',
            'waiting_grant_mobiles' => 'Waiting Grant Mobiles',
            'num' => 'Num',
            'couponid' => 'Couponid',
            'mis_userid_grant' => 'Mis Userid Grant',
            'mis_userid_audit' => 'Mis Userid Audit',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
