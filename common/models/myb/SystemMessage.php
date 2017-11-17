<?php

namespace common\models\myb;

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
            'sys_message_id' => 'Sys Message ID',
            'from_uid' => 'From Uid',
            'from_name' => 'From Name',
            'to_uid' => 'To Uid',
            'to_name' => 'To Name',
            'action_type' => 'Action Type',
            'content_id' => 'Content ID',
            'ctime' => 'Ctime',
            'is_read' => 'Is Read',
            'is_del' => 'Is Del',
            'utime' => 'Utime',
        ];
    }
}
