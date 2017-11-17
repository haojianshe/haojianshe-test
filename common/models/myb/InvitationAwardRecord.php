<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invitation_award_record".
 *
 * @property integer $award_id
 * @property integer $invitation_id
 * @property integer $prizes_id
 * @property integer $award_uid
 * @property integer $award_type
 * @property string $username
 * @property integer $ctime
 * @property integer $handle_time
 * @property string $information
 * @property string $comment
 * @property integer $status
 */
class InvitationAwardRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invitation_award_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invitation_id', 'prizes_id', 'award_uid', 'award_type', 'ctime', 'handle_time'], 'required'],
            [['invitation_id', 'prizes_id', 'award_uid', 'award_type', 'ctime', 'handle_time', 'status'], 'integer'],
            [['information'], 'string'],
            [['username'], 'string', 'max' => 20],
            [['comment'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'award_id' => 'Award ID',
            'invitation_id' => 'Invitation ID',
            'prizes_id' => 'Prizes ID',
            'award_uid' => 'Award Uid',
            'award_type' => 'Award Type',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'handle_time' => 'Handle Time',
            'information' => 'Information',
            'comment' => 'Comment',
            'status' => 'Status',
        ];
    }
}
