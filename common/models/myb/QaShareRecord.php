<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_qa_share_record".
 *
 * @property string $shareid
 * @property string $qaid
 * @property string $uid
 * @property integer $ctime
 */
class QaShareRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_qa_share_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qaid', 'uid'], 'required'],
            [['qaid', 'uid', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shareid' => 'Shareid',
            'qaid' => 'Qaid',
            'uid' => 'Uid',
            'ctime' => 'Ctime',
        ];
    }
}
