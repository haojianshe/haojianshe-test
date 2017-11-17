<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lesson_section".
 *
 * @property integer $sectionid
 * @property string $sectiontitle
 * @property string $desc
 * @property integer $piccount
 * @property integer $lessonid
 * @property integer $listorder
 * @property integer $ctime
 */
class LessonSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lesson_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['piccount', 'lessonid', 'listorder', 'ctime'], 'integer'],
            [['sectiontitle'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sectionid' => 'Sectionid',
            'sectiontitle' => 'Sectiontitle',
            'desc' => 'Desc',
            'piccount' => 'Piccount',
            'lessonid' => 'Lessonid',
            'listorder' => 'Listorder',
            'ctime' => 'Ctime',
        ];
    }
}
