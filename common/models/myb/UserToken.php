<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user_token".
 *
 * @property string $hash_key
 * @property integer $uid
 * @property integer $create_time
 * @property integer $invalid_time
 * @property string $ip
 * @property integer $is_valid
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash_key', 'uid'], 'required'],
            [['uid', 'create_time', 'invalid_time', 'is_valid'], 'integer'],
            [['hash_key'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hash_key' => 'Hash Key',
            'uid' => 'Uid',
            'create_time' => 'Create Time',
            'invalid_time' => 'Invalid Time',
            'ip' => 'Ip',
            'is_valid' => 'Is Valid',
        ];
    }
}
