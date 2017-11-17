<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_sharetask".
 *
 * @property integer $correctid
 * @property integer $sharetime
 * @property integer $changetime
 * @property integer $issuccess
 * @property integer $ischange
 */
class CorrectShareTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_sharetask';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['correctid', 'sharetime'], 'required'],
            [['correctid', 'sharetime', 'changetime', 'issuccess', 'ischange'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'correctid' => 'Correctid',
            'sharetime' => 'Sharetime',
            'changetime' => 'Changetime',
            'issuccess' => 'Issuccess',
            'ischange' => 'Ischange',
        ];
    }
}
