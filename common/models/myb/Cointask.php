<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_cointask".
 *
 * @property integer $taskid
 * @property integer $uid
 * @property integer $tasktype
 * @property integer $times
 * @property integer $taskdate
 * @property string $dataremark
 * @property integer $ctime
 */
class Cointask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_cointask';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'tasktype', 'times', 'taskdate', 'ctime'], 'required'],
            [['uid', 'tasktype', 'times', 'taskdate', 'ctime'], 'integer'],
            [['dataremark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taskid' => 'Taskid',
            'uid' => 'Uid',
            'tasktype' => 'Tasktype',
            'times' => 'Times',
            'taskdate' => 'Taskdate',
            'dataremark' => 'Dataremark',
            'ctime' => 'Ctime',
        ];
    }
}
