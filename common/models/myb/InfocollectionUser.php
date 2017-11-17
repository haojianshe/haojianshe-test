<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_infocollection_user".
 *
 * @property integer $uid
 * @property integer $status
 * @property integer $ctime
 */
class InfocollectionUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_infocollection_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'status', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
