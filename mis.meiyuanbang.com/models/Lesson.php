<?php

namespace mis\models;

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
            [['username'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lessonid' => '课程的id',
            'title' => '考点标题',
            'desc' => 'Desc',
            'maintype' => '课程1级分类',
            'subtype' => '课程2级分类',
            'hits' => '点击量',
            'cmtcount' => '评论数',
            'supportcount' => '顶的次数',
            'status' => '状态 0正常 1删除 2未发布',
            'username' => '添加者名称',
            'ctime' => '创建日期',
        ];
    }
}
