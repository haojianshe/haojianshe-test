<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user_detail".
 *
 * @property integer $uid
 * @property string $sname
 * @property string $avatar
 * @property integer $professionid
 * @property integer $genderid
 * @property integer $provinceid
 * @property string $city
 * @property string $intro
 * @property string $school
 * @property integer $ukind
 * @property integer $ukind_verify
 * @property string $featureflag
 * @property integer $role_type
 * @property integer $studio_type
 * @property integer $city_id
 * @property integer $school_id
 * @property integer $area_id
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sname', 'avatar'], 'required'],
            [['uid', 'professionid', 'genderid', 'provinceid', 'ukind', 'ukind_verify', 'featureflag', 'role_type', 'studio_type', 'city_id', 'school_id', 'area_id'], 'integer'],
            [['avatar'], 'string'],
            [['sname'], 'string', 'max' => 30],
            [['city'], 'string', 'max' => 60],
            [['intro'], 'string', 'max' => 255],
            [['school'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'sname' => 'Sname',
            'avatar' => 'Avatar',
            'professionid' => 'Professionid',
            'genderid' => 'Genderid',
            'provinceid' => 'Provinceid',
            'city' => 'City',
            'intro' => 'Intro',
            'school' => 'School',
            'ukind' => 'Ukind',
            'ukind_verify' => 'Ukind Verify',
            'featureflag' => 'Featureflag',
            'role_type' => 'Role Type',
            'studio_type' => 'Studio Type',
            'city_id' => 'City ID',
            'school_id' => 'School ID',
            'area_id' => 'Area ID',
        ];
    }
}
