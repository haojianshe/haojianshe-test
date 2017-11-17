<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_news".
 *
 * @property integer $newsid
 * @property integer $catid
 * @property string $title
 * @property string $thumb
 * @property string $keywords
 * @property string $desc
 * @property integer $listorder
 * @property integer $status
 * @property string $username
 * @property string $ctime
 * @property string $utime
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catid', 'listorder', 'status', 'ctime', 'utime'], 'integer'],
            [['desc'], 'string'],
            [['title'], 'string', 'max' => 80],
            [['thumb'], 'string', 'max' => 100],
            [['keywords'], 'string', 'max' => 40],
            [['username'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsid' => 'Newsid',
            'catid' => 'Catid',
            'title' => 'Title',
            'thumb' => 'Thumb',
            'keywords' => 'Keywords',
            'desc' => 'Desc',
            'listorder' => 'Listorder',
            'status' => 'Status',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
