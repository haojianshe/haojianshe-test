<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lecture".
 *
 * @property integer $newsid
 * @property integer $lecture_level1
 * @property integer $lecture_level2
 * @property integer $status
 * @property integer $publishtime
 * @property integer $is_in_list
 * @property integer $stick_date
 * @property integer $newstype
 * @property integer $thumbtype
 * @property integer $content_type
 * @property string $courseids
 * @property string $proviceids
 * @property string $professionids
 */
class Lecture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lecture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid'], 'required'],
            [['newsid', 'lecture_level1', 'lecture_level2', 'status', 'publishtime', 'is_in_list', 'stick_date', 'newstype', 'thumbtype', 'content_type'], 'integer'],
            [['courseids', 'professionids'], 'string', 'max' => 100],
            [['proviceids'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'lecture_level1' => 'Lecture Level1',
            'lecture_level2' => 'Lecture Level2',
            'status' => 'Status',
            'publishtime' => 'Publishtime',
            'is_in_list' => 'Is In List',
            'stick_date' => 'Stick Date',
            'newstype' => 'Newstype',
            'thumbtype' => 'Thumbtype',
            'content_type' => 'Content Type',
            'courseids' => 'Courseids',
            'proviceids' => 'Proviceids',
            'professionids' => 'Professionids',
        ];
    }
}
