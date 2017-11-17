<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_payteacher_arrange".
 *
 * @property integer $arrangeid
 * @property string $teacheruids
 * @property integer $btime
 * @property integer $etime
 * @property integer $ctime
 */
class CorrectPayteacherArrange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_payteacher_arrange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacheruids'], 'string'],
            [['btime', 'etime', 'ctime'], 'required'],
            [['btime', 'etime', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'arrangeid' => 'Arrangeid',
            'teacheruids' => 'Teacheruids',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'ctime' => 'Ctime',
        ];
    }
}
