<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_activity".
 *
 * @property integer $activityid
 * @property string $title
 * @property integer $teacheruid
 * @property string $activity_img
 * @property integer $activity_stime
 * @property integer $activity_etime
 * @property integer $ctime
 * @property integer $reg_etime
 * @property integer $is_live
 * @property integer $live_stime
 * @property integer $live_etime
 * @property string $live_url
 * @property integer $is_recording
 * @property string $recording_url
 * @property integer $max_count
 * @property integer $correct_count
 * @property integer $gameid
 * @property integer $status
 * @property string $share_title
 * @property string $share_desc
 * @property string $share_img
 * @property string $submit_stitle
 * @property string $submit_sdesc
 */
class DkActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'teacheruid', 'activity_img', 'activity_stime', 'activity_etime', 'ctime', 'reg_etime', 'max_count', 'correct_count', 'gameid'], 'required'],
            [['teacheruid', 'activity_stime', 'activity_etime', 'ctime', 'reg_etime', 'is_live', 'live_stime', 'live_etime', 'is_recording', 'max_count', 'correct_count', 'gameid', 'status'], 'integer'],
            [['title', 'share_desc', 'share_img', 'submit_sdesc'], 'string', 'max' => 200],
            [['activity_img', 'live_url', 'recording_url'], 'string', 'max' => 255],
            [['share_title', 'submit_stitle'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activityid' => 'Activityid',
            'title' => 'Title',
            'teacheruid' => 'Teacheruid',
            'activity_img' => 'Activity Img',
            'activity_stime' => 'Activity Stime',
            'activity_etime' => 'Activity Etime',
            'ctime' => 'Ctime',
            'reg_etime' => 'Reg Etime',
            'is_live' => 'Is Live',
            'live_stime' => 'Live Stime',
            'live_etime' => 'Live Etime',
            'live_url' => 'Live Url',
            'is_recording' => 'Is Recording',
            'recording_url' => 'Recording Url',
            'max_count' => 'Max Count',
            'correct_count' => 'Correct Count',
            'gameid' => 'Gameid',
            'status' => 'Status',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'share_img' => 'Share Img',
            'submit_stitle' => 'Submit Stitle',
            'submit_sdesc' => 'Submit Sdesc',
        ];
    }
}
