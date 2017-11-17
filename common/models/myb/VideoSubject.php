<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_video_subject".
 *
 * @property integer $subjectid
 * @property string $subject_title
 * @property string $subject_pic
 * @property string $username
 * @property integer $listorder
 * @property integer $ctime
 * @property integer $hits
 * @property string $share_title
 * @property string $share_desc
 * @property string $share_img
 * @property integer $status
 * @property integer $subject_type
 */
class VideoSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_video_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_title', 'subject_pic', 'ctime'], 'required'],
            [['listorder', 'ctime', 'hits', 'status', 'subject_type'], 'integer'],
            [['subject_title'], 'string', 'max' => 100],
            [['subject_pic'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 20],
            [['share_title'], 'string', 'max' => 50],
            [['share_desc', 'share_img'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subjectid' => 'Subjectid',
            'subject_title' => 'Subject Title',
            'subject_pic' => 'Subject Pic',
            'username' => 'Username',
            'listorder' => 'Listorder',
            'ctime' => 'Ctime',
            'hits' => 'Hits',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'share_img' => 'Share Img',
            'status' => 'Status',
            'subject_type' => 'Subject Type',
        ];
    }
}
