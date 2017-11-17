<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_user_correct".
 *
 * @property integer $uid
 * @property integer $issketch
 * @property integer $isdrawing
 * @property integer $iscolor
 * @property integer $isdesign
 * @property integer $gaincoin
 * @property integer $queuenum
 * @property integer $correctnum
 * @property integer $isprivate
 * @property integer $status
 * @property integer $isactivity
 * @property integer $correct_fee
 * @property integer $correct_fee_ios
 */
class UserCorrect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_user_correct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'isprivate'], 'required'],
            [['uid', 'issketch', 'isdrawing', 'iscolor', 'isdesign', 'gaincoin', 'queuenum', 'correctnum', 'isprivate', 'status', 'isactivity', 'correct_fee', 'correct_fee_ios'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'issketch' => 'Issketch',
            'isdrawing' => 'Isdrawing',
            'iscolor' => 'Iscolor',
            'isdesign' => 'Isdesign',
            'gaincoin' => 'Gaincoin',
            'queuenum' => 'Queuenum',
            'correctnum' => 'Correctnum',
            'isprivate' => 'Isprivate',
            'status' => 'Status',
            'isactivity' => 'Isactivity',
            'correct_fee' => 'Correct Fee',
            'correct_fee_ios' => 'Correct Fee Ios',
        ];
    }
}
