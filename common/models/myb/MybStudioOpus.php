<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_opus".
 *
 * @property string $studioopusid
 * @property integer $studiomenuid
 * @property integer $uid
 * @property integer $resourceid
 * @property integer $ctime
 * @property integer $listorder
 * @property integer $status
 * @property string $opus_title
 */
class MybStudioOpus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_opus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['studiomenuid', 'uid', 'resourceid', 'ctime', 'listorder', 'status'], 'integer'],
            [['ctime', 'status'], 'required'],
            [['opus_title'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studioopusid' => 'Studioopusid',
            'studiomenuid' => 'Studiomenuid',
            'uid' => 'Uid',
            'resourceid' => 'Resourceid',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
            'status' => 'Status',
            'opus_title' => 'Opus Title',
        ];
    }
}
