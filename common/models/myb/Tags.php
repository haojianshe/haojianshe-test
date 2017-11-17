<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_tags".
 *
 * @property string $tagid
 * @property string $taggroupid
 * @property string $tag_name
 * @property integer $status
 * @property integer $ctime
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taggroupid', 'status', 'ctime'], 'required'],
            [['taggroupid', 'status', 'ctime'], 'integer'],
            [['tag_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tagid' => 'Tagid',
            'taggroupid' => 'Taggroupid',
            'tag_name' => 'Tag Name',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
