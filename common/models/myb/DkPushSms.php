<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_push_sms".
 *
 * @property integer $pushsmsid
 * @property integer $type
 * @property integer $sid
 * @property string $taskid
 * @property integer $status
 * @property string $content
 * @property integer $ptime
 * @property integer $ctime
 * @property string $message
 * @property integer $successcounts
 * @property string $returnstatus
 * @property integer $totalcounts
 * @property string $mobiles
 */
class DkPushSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_push_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'sid', 'status', 'ptime', 'ctime', 'successcounts', 'totalcounts'], 'integer'],
            [['sid', 'ptime', 'ctime'], 'required'],
            [['mobiles'], 'string'],
            [['taskid'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255],
            [['message'], 'string', 'max' => 200],
            [['returnstatus'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pushsmsid' => 'Pushsmsid',
            'type' => 'Type',
            'sid' => 'Sid',
            'taskid' => 'Taskid',
            'status' => 'Status',
            'content' => 'Content',
            'ptime' => 'Ptime',
            'ctime' => 'Ctime',
            'message' => 'Message',
            'successcounts' => 'Successcounts',
            'returnstatus' => 'Returnstatus',
            'totalcounts' => 'Totalcounts',
            'mobiles' => 'Mobiles',
        ];
    }
}
