<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "ci_system_message".
 *
 * @property string $sys_message_id
 * @property integer $from_uid
 * @property string $from_name
 * @property integer $to_uid
 * @property string $to_name
 * @property integer $action_type
 * @property string $content_id
 * @property integer $ctime
 * @property integer $is_read
 * @property integer $is_del
 * @property integer $utime
 */
class SystemMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_system_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_uid', 'to_uid', 'action_type'], 'required'],
            [['from_uid', 'to_uid', 'action_type', 'content_id', 'ctime', 'is_read', 'is_del', 'utime'], 'integer'],
            [['from_name', 'to_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sys_message_id' => '系统消息id',
            'from_uid' => '发送消息id',
            'from_name' => '产生系统消息的用户名',
            'to_uid' => '接受消息id',
            'to_name' => '接收系统消息的给用户',
            'action_type' => '操作,0:at;1:私信;2:回复评论;3:删除评论;4:关注',
            'content_id' => '与推送消息相关的元素ID，如私信的推送消息中，此ID为私信的ID',
            'ctime' => '系统消息发生时间',
            'is_read' => '消息已读,0:未读;1:已读',
            'is_del' => '消息删除,0:未删除;1:已删除',
            'utime' => '更新时间',
        ];
    }
}
