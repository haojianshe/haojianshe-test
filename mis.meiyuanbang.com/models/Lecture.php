<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_lecture".
 *
 * @property integer $newsid
 * @property integer $lecture_level1
 * @property integer $lecture_level2
 * @property integer $status
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
            [['newsid', 'lecture_level1', 'lecture_level2', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => '使用news表的自增id作为精讲文章id',
            'lecture_level1' => '文章一级分类',
            'lecture_level2' => '文章二级分类',
            'status' => '状态 0正常 1删除',
        ];
    }
}
