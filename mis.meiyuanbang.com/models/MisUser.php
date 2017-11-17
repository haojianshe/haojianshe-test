<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_user".
 *
 * @property integer $mis_userid
 * @property string $mis_username
 * @property string $mis_realname
 * @property string $password
 * @property string $email
 * @property string $department
 * @property string $roleids
 * @property integer $status
 */
class MisUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mis_username', 'mis_realname', 'password'], 'required'],
            [['status'], 'integer'],
            [['mis_username', 'mis_realname', 'email', 'department'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 64],
            [['roleids'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mis_userid' => 'mis用户编号',
            'mis_username' => 'mis用户名称',
            'mis_realname' => '真实姓名',
            'password' => '密码',
            'email' => '邮箱',
            'department' => 'Department',
            'roleids' => '所属角色，一个用户可以隶属于多个角色，用来',
            'status' => '预留用户状态字段，删除 禁止登陆等',
        ];
    }
}