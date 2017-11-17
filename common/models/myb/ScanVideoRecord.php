<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_scan_video_record".
 *
 * @property integer $recordid
 * @property integer $subjecttype
 * @property string $subjectid
 * @property string $ctime
 */
class ScanVideoRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_scan_video_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subjecttype', 'subjectid', 'ctime'], 'required'],
            [['subjecttype', 'subjectid', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recordid' => 'Recordid',
            'subjecttype' => 'Subjecttype',
            'subjectid' => 'Subjectid',
            'ctime' => 'Ctime',
        ];
    }
}
