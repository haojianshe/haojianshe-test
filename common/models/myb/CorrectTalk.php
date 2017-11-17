<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_talk".
 *
 * @property string $talkid
 * @property string $url
 * @property string $mp3url
 * @property integer $duration
 * @property string $location
 */
class CorrectTalk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_talk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'duration'], 'required'],
            [['duration'], 'integer'],
            [['url', 'mp3url'], 'string', 'max' => 128],
            [['location'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'talkid' => 'Talkid',
            'url' => 'Url',
            'mp3url' => 'Mp3url',
            'duration' => 'Duration',
            'location' => 'Location',
        ];
    }
}
