<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_opus".
 *
 * @property string $studioopus
 * @property integer $studiomenuid
 * @property integer $uid
 * @property integer $resourceid
 * @property integer $ctime
 * @property integer $listorder
 */
class StudioOpus extends \yii\db\ActiveRecord
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
            [['studiomenuid', 'uid', 'resourceid', 'ctime', 'listorder'], 'integer'],
            [['ctime'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studioopus' => 'Studioopus',
            'studiomenuid' => 'Studiomenuid',
            'uid' => 'Uid',
            'resourceid' => 'Resourceid',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
        ];
    }
}
