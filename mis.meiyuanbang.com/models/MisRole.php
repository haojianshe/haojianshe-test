<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_role".
 *
 * @property integer $roleid
 * @property string $rolename
 * @property integer $parent_roleid
 * @property string $desc
 */
class MisRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rolename', 'parent_roleid'], 'required'],
            [['parent_roleid'], 'integer'],
            [['rolename'], 'string', 'max' => 50],
            [['desc'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'roleid' => 'Roleid',
            'rolename' => 'Rolename',
            'parent_roleid' => 'Parent Roleid',
            'desc' => 'Desc',
        ];
    }
}