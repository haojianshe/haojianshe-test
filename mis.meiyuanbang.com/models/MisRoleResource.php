<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_role_resource".
 *
 * @property integer $roleid
 * @property string $resourceid
 */
class MisRoleResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_role_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleid', 'resourceid'], 'required'],
            [['roleid'], 'integer'],
            [['resourceid'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'roleid' => '角色id，规定只有子类的角色可以授权',
            'resourceid' => '被授权的资源编号',
        ];
    }
}
