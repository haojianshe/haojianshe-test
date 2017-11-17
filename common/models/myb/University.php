<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_university".
 *
 * @property string $universityid
 * @property string $school
 * @property string $province_id
 * @property string $city_id
 */
class University extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_university';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id'], 'integer'],
            [['school'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'universityid' => 'Universityid',
            'school' => 'School',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
        ];
    }
}
