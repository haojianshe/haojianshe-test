<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "ci_tweet".
 *
 * @property string $tid
 * @property integer $uid
 * @property integer $type
 * @property string $f_catalog
 * @property integer $f_catalog_id
 * @property string $content
 * @property integer $ctime
 * @property integer $is_del
 * @property integer $dtime
 * @property string $img
 * @property string $s_catalog
 * @property integer $s_catalog_id
 * @property string $tags
 * @property string $resource_id
 * @property integer $flag
 * @property string $title
 * @property integer $hits
 * @property integer $utime
 * @property integer $correctid
 * @property integer $lessonid
 */
class Tweet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_tweet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime'], 'required'],
            [['uid', 'type', 'f_catalog_id', 'ctime', 'is_del', 'dtime', 's_catalog_id', 'flag', 'hits', 'utime', 'correctid', 'lessonid'], 'integer'],
            [['content', 'img'], 'string'],
            [['f_catalog', 's_catalog'], 'string', 'max' => 30],
            [['tags'], 'string', 'max' => 100],
            [['resource_id'], 'string', 'max' => 128],
            [['title'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tid' => 'Tid',
            'uid' => 'Uid',
            'type' => 'Type',
            'f_catalog' => 'F Catalog',
            'f_catalog_id' => 'F Catalog ID',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'is_del' => 'Is Del',
            'dtime' => 'Dtime',
            'img' => 'Img',
            's_catalog' => 'S Catalog',
            's_catalog_id' => 'S Catalog ID',
            'tags' => 'Tags',
            'resource_id' => 'Resource ID',
            'flag' => 'Flag',
            'title' => 'Title',
            'hits' => 'Hits',
            'utime' => 'Utime',
            'correctid' => 'Correctid',
            'lessonid' => 'Lessonid',
        ];
    }
}
