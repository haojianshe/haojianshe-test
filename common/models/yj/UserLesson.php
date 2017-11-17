<?php

namespace common\models\yj;

use Yii;

/**
 * This is the model class for table "yj_user_lesson".
 *
 * @property integer $lessonid
 * @property integer $uid
 * @property string $lesson_name
 * @property integer $lesson_length
 * @property integer $create_time
 * @property integer $lesson_count
 * @property integer $status
 * @property string $mark
 */
class UserLesson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yj_user_lesson';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'lesson_length', 'create_time', 'lesson_count', 'status'], 'integer'],
            [['mark'], 'string'],
            [['lesson_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lessonid' => 'Lessonid',
            'uid' => 'Uid',
            'lesson_name' => 'Lesson Name',
            'lesson_length' => 'Lesson Length',
            'create_time' => 'Create Time',
            'lesson_count' => 'Lesson Count',
            'status' => 'Status',
            'mark' => 'Mark',
        ];
    }
}
