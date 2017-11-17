<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_sound_resource".
 *
 * @property integer $soundid
 * @property integer $sound_type
 * @property integer $duration
 * @property integer $filetype
 * @property integer $size
 * @property string $filename
 * @property string $desc
 * @property string $sourceurl
 * @property integer $status
 * @property integer $ctime
 * @property string $imgurl
 */
class SoundResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_sound_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sound_type', 'duration', 'filetype', 'size', 'status', 'ctime'], 'integer'],
            [['duration', 'size', 'filename', 'desc', 'sourceurl', 'ctime'], 'required'],
            [['filename'], 'string', 'max' => 200],
            [['desc', 'sourceurl', 'imgurl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'soundid' => 'Soundid',
            'sound_type' => 'Sound Type',
            'duration' => 'Duration',
            'filetype' => 'Filetype',
            'size' => 'Size',
            'filename' => 'Filename',
            'desc' => 'Desc',
            'sourceurl' => 'Sourceurl',
            'status' => 'Status',
            'ctime' => 'Ctime',
            'imgurl' => 'Imgurl',
        ];
    }
}
