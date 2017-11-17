<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user".
 *
 * @property integer $id
 * @property string $pass_word
 * @property string $pass_mark
 * @property string $umobile
 * @property integer $login_type
 * @property string $oauth_key
 * @property string $unionid
 * @property integer $create_time
 * @property string $oauth_type
 * @property integer $register_status
 * @property string $qd
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pass_word', 'pass_mark'], 'required'],
            [['login_type', 'create_time', 'register_status'], 'integer'],
            [['pass_word'], 'string', 'max' => 64],
            [['pass_mark', 'oauth_type'], 'string', 'max' => 10],
            [['umobile'], 'string', 'max' => 11],
            [['oauth_key', 'unionid', 'qd'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pass_word' => 'Pass Word',
            'pass_mark' => 'Pass Mark',
            'umobile' => 'Umobile',
            'login_type' => 'Login Type',
            'oauth_key' => 'Oauth Key',
            'unionid' => 'Unionid',
            'create_time' => 'Create Time',
            'oauth_type' => 'Oauth Type',
            'register_status' => 'Register Status',
            'qd' => 'Qd',
        ];
    }
}
