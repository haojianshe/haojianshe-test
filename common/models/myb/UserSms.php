<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user_sms".
 *
 * @property integer $sid
 * @property integer $uid
 * @property string $mobile
 * @property string $verifycode
 * @property string $identifier
 * @property string $operate
 * @property integer $status
 * @property integer $valid
 * @property string $ip
 * @property string $ip_long
 * @property integer $ctime_keep
 * @property integer $ctime_year
 * @property integer $ctime_yday
 * @property integer $ctime
 */
class UserSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'mobile', 'status', 'valid', 'ip_long', 'ctime_keep', 'ctime_year', 'ctime_yday', 'ctime'], 'integer'],
            [['status'], 'required'],
            [['verifycode'], 'string', 'max' => 8],
            [['identifier'], 'string', 'max' => 50],
            [['operate'], 'string', 'max' => 1],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => 'Sid',
            'uid' => 'Uid',
            'mobile' => 'Mobile',
            'verifycode' => 'Verifycode',
            'identifier' => 'Identifier',
            'operate' => 'Operate',
            'status' => 'Status',
            'valid' => 'Valid',
            'ip' => 'Ip',
            'ip_long' => 'Ip Long',
            'ctime_keep' => 'Ctime Keep',
            'ctime_year' => 'Ctime Year',
            'ctime_yday' => 'Ctime Yday',
            'ctime' => 'Ctime',
        ];
    }
}
