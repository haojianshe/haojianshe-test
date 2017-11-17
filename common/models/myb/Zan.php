<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_zan".
 *
 * @property integer $uid
 * @property string $tid
 * @property string $username
 * @property integer $owneruid
 * @property integer $ctime
 */
class Zan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_zan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'tid', 'owneruid'], 'required'],
            [['uid', 'tid', 'owneruid', 'ctime'], 'integer'],
            [['username'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'tid' => 'Tid',
            'username' => 'Username',
            'owneruid' => 'Owneruid',
            'ctime' => 'Ctime',
        ];
    }
}
