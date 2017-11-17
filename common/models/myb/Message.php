<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_message".
 *
 * @property integer $mid
 * @property integer $from_uid
 * @property string $content
 * @property integer $mtype
 * @property integer $ctime
 * @property integer $to_uid
 * @property integer $from_del
 * @property integer $to_del
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_uid', 'content', 'ctime', 'to_uid'], 'required'],
            [['from_uid', 'mtype', 'ctime', 'to_uid', 'from_del', 'to_del'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mid' => 'Mid',
            'from_uid' => 'From Uid',
            'content' => 'Content',
            'mtype' => 'Mtype',
            'ctime' => 'Ctime',
            'to_uid' => 'To Uid',
            'from_del' => 'From Del',
            'to_del' => 'To Del',
        ];
    }
}
