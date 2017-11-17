<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_agent_invite".
 *
 * @property integer $invite_id
 * @property integer $agent_userid
 * @property string $user_tel
 * @property integer $ctime
 * @property integer $agent_type
 */
class AgentInvite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_agent_invite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_userid', 'ctime', 'agent_type'], 'integer'],
            [['user_tel'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invite_id' => 'Invite ID',
            'agent_userid' => 'Agent Userid',
            'user_tel' => 'User Tel',
            'ctime' => 'Ctime',
            'agent_type' => 'Agent Type',
        ];
    }
}
