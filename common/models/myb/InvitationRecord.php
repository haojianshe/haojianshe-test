<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invitation_record".
 *
 * @property integer $record_id
 * @property integer $invitation_id
 * @property integer $invitation_uid
 * @property string $invitee_phone
 * @property integer $ctime
 * @property integer $status
 * @property integer $invitee_uid
 */
class InvitationRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invitation_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invitation_id', 'invitation_uid', 'invitee_phone', 'ctime', 'invitee_uid'], 'required'],
            [['invitation_id', 'invitation_uid', 'ctime', 'status', 'invitee_uid'], 'integer'],
            [['invitee_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => 'Record ID',
            'invitation_id' => 'Invitation ID',
            'invitation_uid' => 'Invitation Uid',
            'invitee_phone' => 'Invitee Phone',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'invitee_uid' => 'Invitee Uid',
        ];
    }
}
