<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_agent_user".
 *
 * @property integer $agent_userid
 * @property string $agent_tel
 * @property string $password
 * @property string $agent_name
 * @property string $agent_contact
 * @property integer $agent_type
 * @property integer $revenue_per
 * @property integer $ios_revenue_per
 * @property integer $parent_agent_userid
 * @property integer $status
 * @property integer $ctime
 */
class AgentUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_agent_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_type', 'revenue_per', 'ios_revenue_per', 'parent_agent_userid', 'status', 'ctime'], 'integer'],
            [['agent_tel'], 'string', 'max' => 11],
            [['password', 'agent_contact'], 'string', 'max' => 20],
            [['agent_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agent_userid' => 'Agent Userid',
            'agent_tel' => 'Agent Tel',
            'password' => 'Password',
            'agent_name' => 'Agent Name',
            'agent_contact' => 'Agent Contact',
            'agent_type' => 'Agent Type',
            'revenue_per' => 'Revenue Per',
            'ios_revenue_per' => 'Ios Revenue Per',
            'parent_agent_userid' => 'Parent Agent Userid',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
