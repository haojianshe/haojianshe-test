<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_teacher_folder".
 *
 * @property integer $folderid
 * @property string $foldername
 * @property integer $teacher_uid
 * @property integer $parent_folderid
 * @property integer $pic_count
 * @property integer $ctime
 */
class CorrectTeacherFolder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_teacher_folder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['foldername', 'teacher_uid', 'ctime'], 'required'],
            [['teacher_uid', 'parent_folderid', 'pic_count', 'ctime'], 'integer'],
            [['foldername'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'folderid' => 'Folderid',
            'foldername' => 'Foldername',
            'teacher_uid' => 'Teacher Uid',
            'parent_folderid' => 'Parent Folderid',
            'pic_count' => 'Pic Count',
            'ctime' => 'Ctime',
        ];
    }
}
