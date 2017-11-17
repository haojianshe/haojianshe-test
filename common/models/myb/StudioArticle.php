<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_article".
 *
 * @property string $articleid
 * @property string $studiomenuid
 * @property string $newsid
 * @property integer $listorder
 * @property integer $ctime
 * @property integer $cover_type
 * @property integer $article_type
 */
class StudioArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studiomenuid', 'newsid'], 'required'],
            [['studiomenuid', 'newsid', 'listorder', 'ctime', 'cover_type', 'article_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'articleid' => 'Articleid',
            'studiomenuid' => 'Studiomenuid',
            'newsid' => 'Newsid',
            'listorder' => 'Listorder',
            'ctime' => 'Ctime',
            'cover_type' => 'Cover Type',
            'article_type' => 'Article Type',
        ];
    }
}
