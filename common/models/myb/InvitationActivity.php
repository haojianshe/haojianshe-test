<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invitation_activity".
 *
 * @property string $invitation_id
 * @property integer $ctime
 * @property integer $btime
 * @property integer $etime
 * @property integer $award_time
 * @property string $username
 * @property string $activity_url
 * @property string $activity_invitee_url
 * @property string $honorees_instruction
 * @property string $activity_rules
 * @property string $share_title
 * @property string $sms_copy
 * @property string $share_desc
 * @property string $share_img
 * @property integer $invited_id
 * @property string $prizes_ids
 * @property integer $status
 */
class InvitationActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invitation_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'btime', 'etime', 'award_time', 'invited_id', 'status'], 'integer'],
            [['activity_rules'], 'string'],
            [['username'], 'string', 'max' => 20],
            [['activity_url', 'activity_invitee_url', 'honorees_instruction', 'sms_copy', 'share_desc', 'share_img'], 'string', 'max' => 200],
            [['share_title'], 'string', 'max' => 50],
            [['prizes_ids'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invitation_id' => 'Invitation ID',
            'ctime' => 'Ctime',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'award_time' => 'Award Time',
            'username' => 'Username',
            'activity_url' => 'Activity Url',
            'activity_invitee_url' => 'Activity Invitee Url',
            'honorees_instruction' => 'Honorees Instruction',
            'activity_rules' => 'Activity Rules',
            'share_title' => 'Share Title',
            'sms_copy' => 'Sms Copy',
            'share_desc' => 'Share Desc',
            'share_img' => 'Share Img',
            'invited_id' => 'Invited ID',
            'prizes_ids' => 'Prizes Ids',
            'status' => 'Status',
        ];
    }
}
