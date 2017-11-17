<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_agent_sms".
 *
 * @property integer $smsid
 * @property integer $uid
 * @property integer $mobile
 * @property string $verifycode
 * @property integer $ctime_keep
 * @property integer $ctime
 */
class AgentSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_agent_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'mobile', 'ctime_keep', 'ctime'], 'integer'],
            [['verifycode'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'smsid' => 'Smsid',
            'uid' => 'Uid',
            'mobile' => 'Mobile',
            'verifycode' => 'Verifycode',
            'ctime_keep' => 'Ctime Keep',
            'ctime' => 'Ctime',
        ];
    }
}
