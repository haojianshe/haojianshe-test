<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_live_sign".
 *
 * @property string $liveid
 * @property string $uid
 * @property string $ctime
 */
class LiveSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_live_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['liveid', 'uid', 'ctime'], 'required'],
            [['liveid', 'uid', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'liveid' => 'Liveid',
            'uid' => 'Uid',
            'ctime' => 'Ctime',
        ];
    }
}
