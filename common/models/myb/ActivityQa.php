<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_activity_qa".
 *
 * @property string $qaid
 * @property string $newsid
 * @property integer $ctime
 * @property integer $ask_limit
 * @property string $answer_uids
 * @property integer $cover_type
 * @property integer $activity_type
 */
class ActivityQa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_activity_qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid', 'answer_uids'], 'required'],
            [['newsid', 'ctime', 'ask_limit', 'cover_type', 'activity_type'], 'integer'],
            [['answer_uids'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qaid' => 'Qaid',
            'newsid' => 'Newsid',
            'ctime' => 'Ctime',
            'ask_limit' => 'Ask Limit',
            'answer_uids' => 'Answer Uids',
            'cover_type' => 'Cover Type',
            'activity_type' => 'Activity Type',
        ];
    }
}
