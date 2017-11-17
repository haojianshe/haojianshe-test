<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_prize_share_record".
 *
 * @property integer $recordid
 * @property integer $activityid
 * @property integer $uid
 * @property string $type
 * @property integer $ctime
 * @property integer $status
 */
class DkPrizeShareRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_prize_share_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityid', 'uid', 'type', 'ctime'], 'required'],
            [['activityid', 'uid', 'ctime', 'status'], 'integer'],
            [['type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recordid' => 'Recordid',
            'activityid' => 'Activityid',
            'uid' => 'Uid',
            'type' => 'Type',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
