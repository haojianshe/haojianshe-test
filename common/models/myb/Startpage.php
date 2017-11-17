<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_startpage".
 *
 * @property integer $pageid
 * @property string $imginfo
 * @property string $jumpurl
 * @property integer $startdate
 * @property integer $expiredate
 * @property integer $status
 * @property string $desc
 * @property integer $ctime
 */
class Startpage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_startpage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imginfo', 'startdate', 'expiredate', 'ctime'], 'required'],
            [['startdate', 'expiredate', 'status', 'ctime'], 'integer'],
            [['imginfo'], 'string', 'max' => 256],
            [['jumpurl'], 'string', 'max' => 128],
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pageid' => 'Pageid',
            'imginfo' => 'Imginfo',
            'jumpurl' => 'Jumpurl',
            'startdate' => 'Startdate',
            'expiredate' => 'Expiredate',
            'status' => 'Status',
            'desc' => 'Desc',
            'ctime' => 'Ctime',
        ];
    }
}
