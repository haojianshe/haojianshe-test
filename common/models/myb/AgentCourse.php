<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_agent_course".
 *
 * @property integer $courseid
 * @property integer $stime
 * @property integer $etime
 * @property integer $status
 * @property integer $ctime
 */
class AgentCourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_agent_course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stime', 'etime', 'status', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseid' => 'Courseid',
            'stime' => 'Stime',
            'etime' => 'Etime',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
