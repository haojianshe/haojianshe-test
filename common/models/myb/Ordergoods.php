<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_ordergoods".
 *
 * @property string $recid
 * @property string $orderid
 * @property integer $uid
 * @property integer $subjecttype
 * @property integer $subjectid
 * @property string $fee
 * @property string $remark
 */
class Ordergoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_ordergoods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'uid', 'subjecttype', 'subjectid', 'fee'], 'required'],
            [['orderid', 'uid', 'subjecttype', 'subjectid'], 'integer'],
            [['fee'], 'number'],
            [['remark'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recid' => 'Recid',
            'orderid' => 'Orderid',
            'uid' => 'Uid',
            'subjecttype' => 'Subjecttype',
            'subjectid' => 'Subjectid',
            'fee' => 'Fee',
            'remark' => 'Remark',
        ];
    }
}
