<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_agent_takemoney".
 *
 * @property integer $takeid
 * @property integer $agent_userid
 * @property string $fee
 * @property string $remark
 * @property integer $ctime
 * @property integer $pay_time
 */
class AgentTakemoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_agent_takemoney';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_userid', 'ctime', 'pay_time'], 'integer'],
            [['fee'], 'required'],
            [['fee'], 'number'],
            [['remark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'takeid' => 'Takeid',
            'agent_userid' => 'Agent Userid',
            'fee' => 'Fee',
            'remark' => 'Remark',
            'ctime' => 'Ctime',
            'pay_time' => 'Pay Time',
        ];
    }
}
