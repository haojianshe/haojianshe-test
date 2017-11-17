<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_orderinfo".
 *
 * @property integer $orderid
 * @property integer $uid
 * @property integer $subjecttype
 * @property integer $mark
 * @property string $fee
 * @property integer $status
 * @property string $ordertitle
 * @property string $orderdesc
 * @property integer $paytype
 * @property integer $paytime
 * @property integer $ctime
 * @property integer $teacheruid
 * @property integer $recommend_from
 * @property integer $usercouponid
 * @property string $coupon_price
 * @property string $order_from
 * @property integer $groupbuyid
 */
class Orderinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_orderinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'subjecttype', 'mark', 'fee', 'ordertitle', 'orderdesc', 'ctime'], 'required'],
            [['uid', 'subjecttype', 'mark', 'status', 'paytype', 'paytime', 'ctime', 'teacheruid', 'recommend_from', 'usercouponid', 'groupbuyid'], 'integer'],
            [['fee', 'coupon_price'], 'number'],
            [['ordertitle'], 'string', 'max' => 128],
            [['orderdesc'], 'string', 'max' => 256],
            [['order_from'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderid' => 'Orderid',
            'uid' => 'Uid',
            'subjecttype' => 'Subjecttype',
            'mark' => 'Mark',
            'fee' => 'Fee',
            'status' => 'Status',
            'ordertitle' => 'Ordertitle',
            'orderdesc' => 'Orderdesc',
            'paytype' => 'Paytype',
            'paytime' => 'Paytime',
            'ctime' => 'Ctime',
            'teacheruid' => 'Teacheruid',
            'recommend_from' => 'Recommend From',
            'usercouponid' => 'Usercouponid',
            'coupon_price' => 'Coupon Price',
            'order_from' => 'Order From',
            'groupbuyid' => 'Groupbuyid',
        ];
    }
}
