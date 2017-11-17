<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_scan_video_record".
 *
 * @property integer $recordid
 * @property integer $uid
 * @property integer $subjecttype
 * @property string $subjectid
 * @property string $ctime
 */
class MybScanVideoRecord extends \yii\db\ActiveRecord
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
            [['uid', 'subjecttype', 'subjectid', 'ctime'], 'required'],
            [['uid', 'subjecttype', 'subjectid', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recordid' => 'Recordid',
            'uid' => 'Uid',
            'subjecttype' => 'Subjecttype',
            'subjectid' => 'Subjectid',
            'ctime' => 'Ctime',
        ];
    }
}
