<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_video_resource".
 *
 * @property integer $videoid
 * @property integer $video_type
 * @property integer $video_length
 * @property integer $maintype
 * @property integer $subtype
 * @property string $filename
 * @property string $coverpic
 * @property string $sourceurl
 * @property string $m3u8url
 * @property string $runid
 * @property string $desc
 * @property integer $status
 * @property integer $ctime
 */
class VideoResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_video_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_type', 'video_length', 'video_size', 'maintype', 'subtype', 'status', 'ctime'], 'integer'],
            [['filename'], 'string', 'max' => 50],
            [['coverpic', 'sourceurl', 'm3u8url', 'runid'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'videoid' => 'Videoid',
            'video_type' => 'Video Type',
            'video_length' => 'Video Length',
            'video_size' => 'video_size',
            'maintype' => 'Maintype',
            'subtype' => 'Subtype',
            'filename' => 'Filename',
            'coverpic' => 'Coverpic',
            'sourceurl' => 'Sourceurl',
            'm3u8url' => 'M3u8url',
            'runid' => 'Runid',
            'desc' => 'Desc',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
