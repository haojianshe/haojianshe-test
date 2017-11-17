<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_news_data".
 *
 * @property string $newsid
 * @property string $content
 * @property integer $hits
 * @property integer $cmtcount
 * @property integer $supportcount
 * @property string $copyfrom
 * @property string $reserve1
 * @property string $reserve2
 * @property string $reserve3
 */
class NewsData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_news_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid', 'content'], 'required'],
            [['newsid', 'hits', 'cmtcount', 'supportcount'], 'integer'],
            [['content', 'reserve1', 'reserve2', 'reserve3'], 'string'],
            [['copyfrom'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'content' => 'Content',
            'hits' => '浏览量',
            'cmtcount' => '评论数',
            'supportcount' => '点赞数',
            'copyfrom' => '摘自',
            'reserve1' => '预留字段1',
            'reserve2' => '预留字段2',
            'reserve3' => '预留字段3',
        ];
    }
}
