<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_teacher".
 *
 * @property string $studioteacherid
 * @property integer $uid
 * @property integer $uuid
 * @property integer $ctime
 */
class StudioTeacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'uuid', 'ctime'], 'integer'],
            [['ctime'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studioteacherid' => 'Studioteacherid',
            'uid' => 'Uid',
            'uuid' => 'Uuid',
            'ctime' => 'Ctime',
        ];
    }
}
