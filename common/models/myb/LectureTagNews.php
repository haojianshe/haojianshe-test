<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lecture_tag_news".
 *
 * @property string $tag_news_id
 * @property integer $lecture_tagid
 * @property integer $newsid
 * @property string $title
 * @property integer $listorder
 * @property integer $status
 * @property integer $ctime
 */
class LectureTagNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lecture_tag_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lecture_tagid', 'newsid'], 'required'],
            [['lecture_tagid', 'newsid', 'listorder', 'status', 'ctime'], 'integer'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_news_id' => 'Tag News ID',
            'lecture_tagid' => 'Lecture Tagid',
            'newsid' => 'Newsid',
            'title' => 'Title',
            'listorder' => 'Listorder',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
