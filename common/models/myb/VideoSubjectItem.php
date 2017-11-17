<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_video_subject_item".
 *
 * @property string $itemid
 * @property integer $subjectid
 * @property integer $courseid
 * @property integer $listorder
 * @property integer $ctime
 */
class VideoSubjectItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_video_subject_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subjectid', 'courseid'], 'required'],
            [['subjectid', 'courseid', 'listorder', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemid' => 'Itemid',
            'subjectid' => 'Subjectid',
            'courseid' => 'Courseid',
            'listorder' => 'Listorder',
            'ctime' => 'Ctime',
        ];
    }
}
