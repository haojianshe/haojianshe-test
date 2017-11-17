<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course_sharelotto".
 *
 * @property integer $recordid
 * @property integer $courseid
 * @property integer $uid
 * @property string $type
 * @property integer $ctime
 */
class CourseSharelotto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course_sharelotto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['courseid', 'uid', 'type', 'ctime'], 'required'],
            [['courseid', 'uid', 'ctime'], 'integer'],
            [['type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recordid' => 'Recordid',
            'courseid' => 'Courseid',
            'uid' => 'Uid',
            'type' => 'Type',
            'ctime' => 'Ctime',
        ];
    }
}
