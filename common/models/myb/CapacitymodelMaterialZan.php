<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_capacitymodel_material_zan".
 *
 * @property integer $zanid
 * @property integer $uid
 * @property string $materialid
 * @property string $username
 * @property integer $owneruid
 * @property integer $ctime
 */
class CapacitymodelMaterialZan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_capacitymodel_material_zan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'materialid', 'owneruid', 'ctime'], 'required'],
            [['uid', 'materialid', 'owneruid', 'ctime'], 'integer'],
            [['username'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zanid' => 'Zanid',
            'uid' => 'Uid',
            'materialid' => 'Materialid',
            'username' => 'Username',
            'owneruid' => 'Owneruid',
            'ctime' => 'Ctime',
        ];
    }
}
