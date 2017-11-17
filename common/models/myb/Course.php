<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course".
 *
 * @property integer $courseid
 * @property string $title
 * @property string $teacher_desc
 * @property string $teacheruid
 * @property string $f_catalog_id
 * @property string $s_catalog_id
 * @property string $ctime
 * @property string $hits_basic
 * @property string $hits
 * @property string $thumb_url
 * @property string $supportcount
 * @property string $cmtcount
 * @property string $share_img
 * @property string $share_title
 * @property string $share_desc
 * @property integer $status
 * @property string $content
 * @property string $username
 * @property string $course_bounty_fee_ios
 * @property string $course_bounty_fee
 * @property string $course_price
 * @property string $course_sale_price
 * @property string $course_price_ios
 * @property integer $buy_type
 * @property integer $is_free
 * @property string $customer_service
 * @property integer $game_start_time
 * @property integer $game_end_time
 * @property integer $gameid
 * @property integer $learn_videoid
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'teacher_desc', 'teacheruid', 'f_catalog_id', 's_catalog_id', 'ctime', 'thumb_url', 'share_img', 'share_title', 'share_desc', 'is_free'], 'required'],
            [['teacheruid', 'f_catalog_id', 's_catalog_id', 'ctime', 'hits_basic', 'hits', 'supportcount', 'cmtcount', 'status', 'buy_type', 'is_free', 'game_start_time', 'game_end_time', 'gameid', 'learn_videoid'], 'integer'],
            [['content'], 'string'],
            [['course_bounty_fee_ios', 'course_bounty_fee', 'course_price', 'course_sale_price', 'course_price_ios'], 'number'],
            [['title', 'teacher_desc', 'thumb_url', 'share_img', 'share_desc'], 'string', 'max' => 255],
            [['share_title'], 'string', 'max' => 50],
            [['username'], 'string', 'max' => 200],
            [['customer_service'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseid' => 'Courseid',
            'title' => 'Title',
            'teacher_desc' => 'Teacher Desc',
            'teacheruid' => 'Teacheruid',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'ctime' => 'Ctime',
            'hits_basic' => 'Hits Basic',
            'hits' => 'Hits',
            'thumb_url' => 'Thumb Url',
            'supportcount' => 'Supportcount',
            'cmtcount' => 'Cmtcount',
            'share_img' => 'Share Img',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'status' => 'Status',
            'content' => 'Content',
            'username' => 'Username',
            'course_bounty_fee_ios' => 'Course Bounty Fee Ios',
            'course_bounty_fee' => 'Course Bounty Fee',
            'course_price' => 'Course Price',
            'course_sale_price' => 'Course Sale Price',
            'course_price_ios' => 'Course Price Ios',
            'buy_type' => 'Buy Type',
            'is_free' => 'Is Free',
            'customer_service' => 'Customer Service',
            'game_start_time' => 'Game Start Time',
            'game_end_time' => 'Game End Time',
            'gameid' => 'Gameid',
            'learn_videoid' => 'Learn Videoid',
        ];
    }
}
