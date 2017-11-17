<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lk".
 *
 * @property integer $lkid
 * @property string $title
 * @property integer $ctime
 * @property integer $btime
 * @property integer $etime
 * @property integer $adminid
 * @property integer $provinceid
 * @property integer $status
 * @property string $share_title
 * @property string $share_desc
 * @property string $share_img
 * @property string $newsid
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $submit_count
 * @property string $signup_limit
 * @property string $teacher_id
 * @property integer $rank_status
 * @property integer $activity_type
 */
class Lk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'btime', 'etime', 'adminid', 'provinceid', 'status', 'newsid', 'start_time', 'end_time', 'submit_count', 'signup_limit', 'teacher_id', 'rank_status', 'activity_type'], 'integer'],
            [['adminid', 'provinceid', 'newsid'], 'required'],
            [['title', 'share_title'], 'string', 'max' => 50],
            [['share_desc', 'share_img'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lkid' => 'Lkid',
            'title' => 'Title',
            'ctime' => 'Ctime',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'adminid' => 'Adminid',
            'provinceid' => 'Provinceid',
            'status' => 'Status',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'share_img' => 'Share Img',
            'newsid' => 'Newsid',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'submit_count' => 'Submit Count',
            'signup_limit' => 'Signup Limit',
            'teacher_id' => 'Teacher ID',
            'rank_status' => 'Rank Status',
            'activity_type' => 'Activity Type',
        ];
    }
}
