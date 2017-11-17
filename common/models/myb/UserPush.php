<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user_push".
 *
 * @property integer $id
 * @property string $xg_device_token
 * @property string $ios_device_token
 * @property integer $device_type
 * @property integer $uid
 * @property string $tags
 */
class UserPush extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user_push';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['xg_device_token', 'device_type'], 'required'],
            [['device_type', 'uid'], 'integer'],
            [['xg_device_token', 'ios_device_token', 'tags'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'xg_device_token' => 'Xg Device Token',
            'ios_device_token' => 'Ios Device Token',
            'device_type' => 'Device Type',
            'uid' => 'Uid',
            'tags' => 'Tags',
        ];
    }
}
