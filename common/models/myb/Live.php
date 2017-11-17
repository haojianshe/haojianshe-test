<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_live".
 *
 * @property integer $liveid
 * @property string $teacheruid
 * @property string $live_title
 * @property string $teacher_desc
 * @property string $live_thumb_url
 * @property string $recording_thumb_url
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property string $live_content
 * @property string $live_price
 * @property string $recording_price
 * @property string $live_push_url
 * @property string $live_display_url
 * @property string $videoid
 * @property string $start_time
 * @property string $end_time
 * @property string $hits_basic
 * @property string $hits
 * @property string $supportcount
 * @property string $cmtcount
 * @property string $adminuid
 * @property string $username
 * @property string $ctime
 * @property integer $status
 * @property string $share_title
 * @property string $share_desc
 * @property string $share_img
 * @property integer $advid
 * @property integer $playtype
 * @property string $live_ios_price
 * @property string $recording_ios_price
 * @property string $customer_service
 */
class Live extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_live';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacheruid', 'live_title', 'teacher_desc', 'live_thumb_url', 'recording_thumb_url', 'f_catalog_id', 's_catalog_id', 'live_push_url', 'live_display_url', 'start_time', 'end_time', 'adminuid', 'ctime'], 'required'],
            [['teacheruid', 'f_catalog_id', 's_catalog_id', 'start_time', 'end_time', 'hits_basic', 'hits', 'supportcount', 'cmtcount', 'adminuid', 'ctime', 'status', 'advid', 'playtype'], 'integer'],
            [['live_content'], 'string'],
            [['live_price', 'recording_price', 'live_ios_price', 'recording_ios_price'], 'number'],
            [['live_title'], 'string', 'max' => 100],
            [['teacher_desc', 'live_thumb_url', 'recording_thumb_url', 'videoid'], 'string', 'max' => 255],
            [['live_push_url', 'live_display_url'], 'string', 'max' => 400],
            [['username'], 'string', 'max' => 20],
            [['share_title'], 'string', 'max' => 50],
            [['share_desc', 'share_img'], 'string', 'max' => 200],
            [['customer_service'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'liveid' => 'Liveid',
            'teacheruid' => 'Teacheruid',
            'live_title' => 'Live Title',
            'teacher_desc' => 'Teacher Desc',
            'live_thumb_url' => 'Live Thumb Url',
            'recording_thumb_url' => 'Recording Thumb Url',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'live_content' => 'Live Content',
            'live_price' => 'Live Price',
            'recording_price' => 'Recording Price',
            'live_push_url' => 'Live Push Url',
            'live_display_url' => 'Live Display Url',
            'videoid' => 'Videoid',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'hits_basic' => 'Hits Basic',
            'hits' => 'Hits',
            'supportcount' => 'Supportcount',
            'cmtcount' => 'Cmtcount',
            'adminuid' => 'Adminuid',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'share_img' => 'Share Img',
            'advid' => 'Advid',
            'playtype' => 'Playtype',
            'live_ios_price' => 'Live Ios Price',
            'recording_ios_price' => 'Recording Ios Price',
            'customer_service' => 'Customer Service',
        ];
    }
}
