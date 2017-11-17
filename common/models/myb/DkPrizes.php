<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_prizes".
 *
 * @property integer $prizesid
 * @property string $title
 * @property integer $type
 * @property string $img
 * @property string $content
 * @property integer $ctime
 * @property integer $status
 */
class DkPrizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_prizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'type', 'img', 'ctime'], 'required'],
            [['type', 'ctime', 'status'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prizesid' => 'Prizesid',
            'title' => 'Title',
            'type' => 'Type',
            'img' => 'Img',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
