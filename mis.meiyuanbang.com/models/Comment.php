<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "eci_comment".
 *
 * @property integer $cid
 * @property integer $uid
 * @property integer $subjecttype
 * @property integer $subjectid
 * @property integer $ctype
 * @property string $content
 * @property integer $ctime
 * @property integer $reply_uid
 * @property integer $reply_cid
 * @property integer $is_del
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eci_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'subjecttype', 'subjectid', 'ctype', 'ctime'], 'required'],
            [['uid', 'subjecttype', 'subjectid', 'ctype', 'ctime', 'reply_uid', 'reply_cid', 'is_del'], 'integer'],
            [['content'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cid' => 'Cid',
            'uid' => 'Uid',
            'subjecttype' => 'Subjecttype',
            'subjectid' => 'Subjectid',
            'ctype' => 'Ctype',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'reply_uid' => 'Reply Uid',
            'reply_cid' => 'Reply Cid',
            'is_del' => 'Is Del',
        ];
    }
}
