<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course_recommend".
 *
 * @property integer $courserecid
 * @property string $recommendid
 * @property string $courseid
 * @property string $ctime
 * @property string $sort_id
 */
class CourseRecommend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recommendid', 'courseid', 'ctime', 'sort_id'], 'required'],
            [['recommendid', 'courseid', 'ctime', 'sort_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courserecid' => 'Courserecid',
            'recommendid' => 'Recommendid',
            'courseid' => 'Courseid',
            'ctime' => 'Ctime',
            'sort_id' => 'Sort ID',
        ];
    }
}
