<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_middle_school".
 *
 * @property string $schoolid
 * @property string $school
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 */
class MiddleSchool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_middle_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'area_id'], 'integer'],
            [['school'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schoolid' => 'Schoolid',
            'school' => 'School',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
        ];
    }
}
