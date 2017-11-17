<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invite".
 *
 * @property integer $invite_id
 * @property integer $invite_userid
 * @property string $umobile
 * @property string $remark
 * @property integer $ctime
 */
class Invite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invite_userid', 'ctime'], 'integer'],
            [['umobile'], 'string', 'max' => 11],
            [['remark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invite_id' => 'Invite ID',
            'invite_userid' => 'Invite Userid',
            'umobile' => 'Umobile',
            'remark' => 'Remark',
            'ctime' => 'Ctime',
        ];
    }
}
