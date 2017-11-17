<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lesson".
 *
 * @property integer $lessonid
 * @property string $title
 * @property string $desc
 * @property integer $maintype
 * @property integer $subtype
 * @property integer $hits
 * @property integer $cmtcount
 * @property integer $supportcount
 * @property integer $status
 * @property string $username
 * @property integer $ctime
 * @property string $coverurl
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lesson';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'maintype', 'subtype', 'ctime'], 'required'],
            [['maintype', 'subtype', 'hits', 'cmtcount', 'supportcount', 'status', 'ctime'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['desc'], 'string', 'max' => 500],
            [['username'], 'string', 'max' => 30],
            [['coverurl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lessonid' => 'Lessonid',
            'title' => 'Title',
            'desc' => 'Desc',
            'maintype' => 'Maintype',
            'subtype' => 'Subtype',
            'hits' => 'Hits',
            'cmtcount' => 'Cmtcount',
            'supportcount' => 'Supportcount',
            'status' => 'Status',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'coverurl' => 'Coverurl',
        ];
    }
}
