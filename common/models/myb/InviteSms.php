<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invite_sms".
 *
 * @property integer $smsid
 * @property integer $uid
 * @property string $mobile
 * @property string $verifycode
 * @property integer $ctime_keep
 * @property integer $ctime
 */
class InviteSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invite_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime_keep', 'ctime'], 'integer'],
            [['mobile'], 'string', 'max' => 11],
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
