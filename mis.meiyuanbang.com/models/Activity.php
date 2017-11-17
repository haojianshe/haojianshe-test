<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_activity".
 *
 * @property integer $newsid
 * @property integer $btime
 * @property integer $etime
 * @property string $signup_url
 * @property string $activity_url
 * @property string $click_button_text
 * @property string $click_type
 * @property string $param1
 * @property string $param2
 * @property integer $status
 * @property integer $costcoin
 * @property integer $activity_type
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid'], 'required'],
            [['newsid', 'btime', 'etime', 'status', 'costcoin', 'activity_type'], 'integer'],
            [['signup_url', 'activity_url'], 'string', 'max' => 512],
            [['click_button_text'], 'string', 'max' => 60],
            [['click_type', 'param1', 'param2'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'signup_url' => 'Signup Url',
            'activity_url' => 'Activity Url',
            'click_button_text' => 'Click Button Text',
            'click_type' => 'Click Type',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'status' => 'Status',
            'costcoin' => 'Costcoin',
            'activity_type' => 'Activity Type',
        ];
    }
}
