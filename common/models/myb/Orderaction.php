<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_orderaction".
 *
 * @property string $actionid
 * @property string $orderid
 * @property integer $uid
 * @property integer $actiontype
 * @property integer $action_status
 * @property integer $actiontime
 * @property string $action_note
 * @property string $mark
 * @property integer $ctime
 */
class Orderaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_orderaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'uid', 'actiontype', 'action_status', 'ctime'], 'required'],
            [['orderid', 'uid', 'actiontype', 'action_status', 'actiontime', 'ctime'], 'integer'],
            [['action_note'], 'string', 'max' => 10000],
            [['mark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'actionid' => 'Actionid',
            'orderid' => 'Orderid',
            'uid' => 'Uid',
            'actiontype' => 'Actiontype',
            'action_status' => 'Action Status',
            'actiontime' => 'Actiontime',
            'action_note' => 'Action Note',
            'mark' => 'Mark',
            'ctime' => 'Ctime',
        ];
    }
}
