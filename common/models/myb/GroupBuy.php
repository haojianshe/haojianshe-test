<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_group_buy".
 *
 * @property integer $groupbuyid
 * @property string $title
 * @property integer $courseid
 * @property string $course_group_fee_ios
 * @property string $course_group_fee
 * @property string $person_count_init
 * @property string $person_count_total
 * @property string $person_count_final
 * @property string $start_time
 * @property string $end_time
 * @property integer $ctime
 * @property integer $status
 * @property integer $person_count_show
 * @property integer $has_notice
 */
class GroupBuy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_group_buy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'courseid', 'start_time', 'end_time', 'ctime'], 'required'],
            [['courseid', 'person_count_init', 'person_count_total', 'person_count_final', 'start_time', 'end_time', 'ctime', 'status', 'person_count_show', 'has_notice'], 'integer'],
            [['course_group_fee_ios', 'course_group_fee'], 'number'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'groupbuyid' => 'Groupbuyid',
            'title' => 'Title',
            'courseid' => 'Courseid',
            'course_group_fee_ios' => 'Course Group Fee Ios',
            'course_group_fee' => 'Course Group Fee',
            'person_count_init' => 'Person Count Init',
            'person_count_total' => 'Person Count Total',
            'person_count_final' => 'Person Count Final',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'person_count_show' => 'Person Count Show',
            'has_notice' => 'Has Notice',
        ];
    }
}
