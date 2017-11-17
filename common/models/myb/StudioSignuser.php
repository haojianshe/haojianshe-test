<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_signuser".
 *
 * @property string $signuserid
 * @property integer $uid
 * @property string $classtypeid
 * @property string $enrollid
 * @property string $name
 * @property string $mobile
 * @property string $QQ
 * @property string $school
 * @property integer $ctime
 */
class StudioSignuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_signuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'classtypeid', 'enrollid'], 'required'],
            [['uid', 'classtypeid', 'enrollid', 'ctime'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['mobile', 'QQ'], 'string', 'max' => 11],
            [['school'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'signuserid' => 'Signuserid',
            'uid' => 'Uid',
            'classtypeid' => 'Classtypeid',
            'enrollid' => 'Enrollid',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'QQ' => 'Qq',
            'school' => 'School',
            'ctime' => 'Ctime',
        ];
    }
}
