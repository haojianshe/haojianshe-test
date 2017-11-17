<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_zhn_article".
 *
 * @property integer $newsid
 * @property integer $article_type
 * @property integer $status
 * @property integer $allowcmt
 * @property string $mark
 */
class ZhnArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_zhn_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid'], 'required'],
            [['newsid', 'article_type', 'status', 'allowcmt'], 'integer'],
            [['mark'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'article_type' => 'Article Type',
            'status' => 'Status',
            'allowcmt' => 'Allowcmt',
            'mark' => 'Mark',
        ];
    }
}
