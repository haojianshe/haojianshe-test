<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_orders".
 *
 * @property integer $ordersid
 * @property string $uid
 * @property integer $subjecttype
 * @property string $sujectid
 * @property string $pay_price
 * @property integer $status
 * @property integer $pay_type
 * @property string $ctime
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'subjecttype', 'sujectid', 'pay_price', 'status', 'pay_type', 'ctime'], 'required'],
            [['uid', 'subjecttype', 'sujectid', 'status', 'pay_type', 'ctime'], 'integer'],
            [['pay_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ordersid' => 'Ordersid',
            'uid' => 'Uid',
            'subjecttype' => 'Subjecttype',
            'sujectid' => 'Sujectid',
            'pay_price' => 'Pay Price',
            'status' => 'Status',
            'pay_type' => 'Pay Type',
            'ctime' => 'Ctime',
        ];
    }
}
