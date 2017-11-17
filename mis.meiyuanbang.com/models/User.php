<?php

namespace mis\models;

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
 * @property integer $create_time
 * @property string $oauth_type
 * @property integer $register_status
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
            [['oauth_key'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增用户id',
            'pass_word' => '密码sign',
            'pass_mark' => '未使用',
            'umobile' => '手机号',
            'login_type' => '注册类型',
            'oauth_key' => '第三方openid',
            'create_time' => '创建时间',
            'oauth_type' => '第三方类型：qq/weixin等',
            'register_status' => '注册状态：0正常1未注册2注册未完整信息',
        ];
    }
}
