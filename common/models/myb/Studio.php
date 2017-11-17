<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio".
 *
 * @property string $uid
 * @property string $studio_mobile
 * @property string $studio_tel
 * @property string $contact_user
 * @property string $username
 * @property string $studio_desc
 * @property integer $ctime
 * @property integer $status
 */
class Studio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime'], 'required'],
            [['uid', 'ctime', 'status'], 'integer'],
            [['studio_desc'], 'string'],
            [['studio_mobile'], 'string', 'max' => 11],
            [['studio_tel'], 'string', 'max' => 15],
            [['contact_user'], 'string', 'max' => 10],
            [['username'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'studio_mobile' => 'Studio Mobile',
            'studio_tel' => 'Studio Tel',
            'contact_user' => 'Contact User',
            'username' => 'Username',
            'studio_desc' => 'Studio Desc',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
