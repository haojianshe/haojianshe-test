<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_live_black".
 *
 * @property string $uid
 * @property string $liveid
 * @property string $no_talking_time
 * @property string $ctime
 */
class bLiveBlack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_live_black';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'liveid', 'no_talking_time', 'ctime'], 'required'],
            [['uid', 'liveid', 'no_talking_time', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'liveid' => 'Liveid',
            'no_talking_time' => 'No Talking Time',
            'ctime' => 'Ctime',
        ];
    }
}
