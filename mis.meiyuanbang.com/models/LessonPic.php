<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_lesson_pic".
 *
 * @property integer $picid
 * @property string $picurl
 * @property integer $picw
 * @property integer $pich
 * @property string $picdesc
 * @property integer $sectionid
 * @property integer $listorder
 */
class LessonPic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lesson_pic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picw', 'pich', 'sectionid', 'listorder'], 'integer'],
            [['sectionid'], 'required'],
            [['picurl', 'picdesc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'picid' => '图片自增id',
            'picurl' => '图片url',
            'picw' => '图片原始宽度',
            'pich' => '图片原始高度',
            'picdesc' => '图片简介',
            'sectionid' => '对应的阶段id',
            'listorder' => '排序字段',
        ];
    }
}
