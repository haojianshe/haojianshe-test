<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lesson_desc".
 *
 * @property integer $lessondescid
 * @property integer $lessonid
 * @property integer $soundid
 * @property string $imgurl
 */
class LessonDesc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lesson_desc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lessonid', 'soundid', 'imgurl'], 'required'],
            [['lessonid', 'soundid'], 'integer'],
            [['imgurl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lessondescid' => 'Lessondescid',
            'lessonid' => 'Lessonid',
            'soundid' => 'Soundid',
            'imgurl' => 'Imgurl',
        ];
    }
}
