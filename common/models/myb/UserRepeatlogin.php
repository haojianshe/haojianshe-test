<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_user_repeatlogin".
 *
 * @property integer $autoid
 * @property integer $uid
 * @property string $token
 * @property string $devicetype
 * @property integer $isprompt
 * @property integer $repeattime
 * @property integer $lastlogintime
 * @property integer $updatetimes
 * @property integer $ctime
 */
class UserRepeatlogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_user_repeatlogin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'token', 'devicetype', 'lastlogintime', 'ctime'], 'required'],
            [['uid', 'isprompt', 'repeattime', 'lastlogintime', 'updatetimes', 'ctime'], 'integer'],
            [['token'], 'string', 'max' => 64],
            [['devicetype'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'autoid' => 'Autoid',
            'uid' => 'Uid',
            'token' => 'Token',
            'devicetype' => 'Devicetype',
            'isprompt' => 'Isprompt',
            'repeattime' => 'Repeattime',
            'lastlogintime' => 'Lastlogintime',
            'updatetimes' => 'Updatetimes',
            'ctime' => 'Ctime',
        ];
    }
}
