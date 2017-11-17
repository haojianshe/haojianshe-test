<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course_section".
 *
 * @property integer $sectionid
 * @property string $section_num
 * @property string $courseid
 * @property string $title
 * @property string $ctime
 * @property integer $status
 */
class CourseSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_num', 'courseid', 'title', 'ctime'], 'required'],
            [['section_num', 'courseid', 'ctime', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sectionid' => 'Sectionid',
            'section_num' => 'Section Num',
            'courseid' => 'Courseid',
            'title' => 'Title',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
