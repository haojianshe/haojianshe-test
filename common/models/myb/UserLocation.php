<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_user_location".
 *
 * @property integer $uid
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $addr
 * @property string $lon
 * @property string $lat
 * @property integer $ctime
 */
class UserLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_user_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'ctime'], 'integer'],
            [['lon', 'lat'], 'number'],
            [['country', 'province', 'city', 'district'], 'string', 'max' => 50],
            [['addr'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'country' => 'Country',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'addr' => 'Addr',
            'lon' => 'Lon',
            'lat' => 'Lat',
            'ctime' => 'Ctime',
        ];
    }
}
