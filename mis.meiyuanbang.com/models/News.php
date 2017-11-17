<?php

namespace mis\models;

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
            'newsid' => '自增id',
            'catid' => '类型：0精讲 1课程 2活动 ',
            'title' => '标题',
            'thumb' => '缩略图',
            'keywords' => '关键词',
            'desc' => '简介',
            'listorder' => '排序字段',
            'status' => '状态 0正常 1删除',
            'username' => '创建用户',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
