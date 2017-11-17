<?php

namespace common\models\myb;

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
            'picid' => 'Picid',
            'picurl' => 'Picurl',
            'picw' => 'Picw',
            'pich' => 'Pich',
            'picdesc' => 'Picdesc',
            'sectionid' => 'Sectionid',
            'listorder' => 'Listorder',
        ];
    }
}
