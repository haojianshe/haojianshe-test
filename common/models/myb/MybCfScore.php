<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_cf_score".
 *
 * @property integer $csid
 * @property string $students_number
 * @property string $students_name
 * @property integer $sm_score
 * @property integer $sx_score
 * @property integer $sc_score
 * @property integer $sum_score
 * @property integer $ctime
 * @property integer $cfid
 * @property integer $count
 */
class MybCfScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_cf_score';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sm_score', 'sx_score', 'sc_score', 'sum_score', 'ctime', 'cfid', 'count'], 'integer'],
            [['count'], 'required'],
            [['students_number'], 'string', 'max' => 50],
            [['students_name'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csid' => 'Csid',
            'students_number' => 'Students Number',
            'students_name' => 'Students Name',
            'sm_score' => 'Sm Score',
            'sx_score' => 'Sx Score',
            'sc_score' => 'Sc Score',
            'sum_score' => 'Sum Score',
            'ctime' => 'Ctime',
            'cfid' => 'Cfid',
            'count' => 'Count',
        ];
    }
}
