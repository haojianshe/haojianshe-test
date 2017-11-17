<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_reward".
 *
 * @property integer $rewardid
 * @property integer $uid
 * @property integer $teacheruid
 * @property integer $gift_id
 * @property integer $gift_price
 * @property string $gift_name
 * @property integer $ctime
 * @property integer $status
 */
class CorrectReward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_reward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'teacheruid', 'gift_id', 'ctime'], 'required'],
            [['uid', 'teacheruid', 'gift_id', 'gift_price', 'ctime', 'status'], 'integer'],
            [['gift_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rewardid' => 'Rewardid',
            'uid' => 'Uid',
            'teacheruid' => 'Teacheruid',
            'gift_id' => 'Gift ID',
            'gift_price' => 'Gift Price',
            'gift_name' => 'Gift Name',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
