<?php

namespace common\models\myb;

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
            'hits' => 'Hits',
            'cmtcount' => 'Cmtcount',
            'supportcount' => 'Supportcount',
            'copyfrom' => 'Copyfrom',
            'reserve1' => 'Reserve1',
            'reserve2' => 'Reserve2',
            'reserve3' => 'Reserve3',
        ];
    }
}
