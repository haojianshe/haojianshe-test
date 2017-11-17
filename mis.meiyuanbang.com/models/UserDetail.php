<?php

namespace mis\models;

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
            [['uid', 'professionid', 'genderid', 'provinceid', 'ukind', 'ukind_verify'], 'integer'],
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
            'uid' => '用户id',
            'sname' => '昵称',
            'avatar' => '头像',
            'professionid' => 'Professionid',
            'genderid' => 'Genderid',
            'provinceid' => '省id',
            'city' => '城市',
            'intro' => '自我介绍',
            'school' => '学校',
            'ukind' => '身份',
            'ukind_verify' => '是否认证',
        ];
    }
}
