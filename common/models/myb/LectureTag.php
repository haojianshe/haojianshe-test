<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lecture_tag".
 *
 * @property string $lecture_tagid
 * @property integer $newsid
 * @property string $tag_title
 * @property integer $status
 * @property integer $listorder
 * @property integer $ctime
 */
class LectureTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lecture_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid'], 'required'],
            [['newsid', 'status', 'listorder', 'ctime'], 'integer'],
            [['tag_title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lecture_tagid' => 'Lecture Tagid',
            'newsid' => 'Newsid',
            'tag_title' => 'Tag Title',
            'status' => 'Status',
            'listorder' => 'Listorder',
            'ctime' => 'Ctime',
        ];
    }
}
