<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "ci_user_token".
 *
 * @property integer $uid
 * @property string $hash_key
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
            [['uid', 'hash_key'], 'required'],
            [['uid', 'create_time', 'invalid_time', 'is_valid'], 'integer'],
            [['hash_key'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 20],
            [['uid', 'hash_key'], 'unique', 'targetAttribute' => ['uid', 'hash_key'], 'message' => 'The combination of 用户id and token hash值 has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户id',
            'hash_key' => 'token hash值',
            'create_time' => '创建时间',
            'invalid_time' => '失效时间',
            'ip' => '用户IP',
            'is_valid' => '是否有效',
        ];
    }
}
