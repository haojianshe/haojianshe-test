<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_region".
 *
 * @property string $region_id
 * @property string $region_name
 * @property string $parent_id
 * @property integer $region_type
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'region_type'], 'integer'],
            [['region_name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'region_id' => 'Region ID',
            'region_name' => 'Region Name',
            'parent_id' => 'Parent ID',
            'region_type' => 'Region Type',
        ];
    }
}
