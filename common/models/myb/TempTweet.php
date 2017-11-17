<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "temp_tweet".
 *
 * @property integer $temptid
 * @property integer $uid
 * @property string $f_catalog
 * @property string $s_catalog
 * @property string $content
 * @property string $tags
 * @property integer $flag
 * @property string $imgurl
 * @property string $resource_id
 * @property integer $tid
 * @property string $ctime
 * @property integer $have_comment_num
 * @property integer $total_comment_num
 */
class TempTweet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp_tweet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'flag', 'tid', 'have_comment_num', 'total_comment_num'], 'integer'],
            [['flag'], 'required'],
            [['f_catalog', 's_catalog', 'content', 'tags', 'imgurl', 'resource_id', 'ctime'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'temptid' => 'Temptid',
            'uid' => 'Uid',
            'f_catalog' => 'F Catalog',
            's_catalog' => 'S Catalog',
            'content' => 'Content',
            'tags' => 'Tags',
            'flag' => 'Flag',
            'imgurl' => 'Imgurl',
            'resource_id' => 'Resource ID',
            'tid' => 'Tid',
            'ctime' => 'Ctime',
            'have_comment_num' => 'Have Comment Num',
            'total_comment_num' => 'Total Comment Num',
        ];
    }
}
