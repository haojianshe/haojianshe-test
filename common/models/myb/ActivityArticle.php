<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_activity_article".
 *
 * @property string $articleid
 * @property string $newsid
 * @property integer $ctime
 * @property integer $cover_type
 */
class ActivityArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_activity_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid'], 'required'],
            [['newsid', 'ctime', 'cover_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'articleid' => 'Articleid',
            'newsid' => 'Newsid',
            'ctime' => 'Ctime',
            'cover_type' => 'Cover Type',
        ];
    }
}
