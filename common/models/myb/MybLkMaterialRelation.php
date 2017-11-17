<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lk_material_relation".
 *
 * @property string $reid
 * @property string $lkid
 * @property string $newsid
 * @property integer $zp_type
 * @property integer $ctime
 * @property integer $zdtime
 * @property integer $status
 */
class MybLkMaterialRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lk_material_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lkid', 'newsid'], 'required'],
            [['lkid', 'newsid', 'zp_type', 'ctime', 'zdtime', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reid' => 'Reid',
            'lkid' => 'Lkid',
            'newsid' => 'Newsid',
            'zp_type' => 'Zp Type',
            'ctime' => 'Ctime',
            'zdtime' => 'Zdtime',
            'status' => 'Status',
        ];
    }
}
