<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_posid_home_user".
 *
 * @property integer $posidid
 * @property integer $uid
 * @property string $img
 * @property string $url
 * @property integer $listorder
 * @property integer $status
 * @property integer $ctime
 */
class PosidHomeUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_posid_home_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'status', 'ctime'], 'required'],
            [['uid', 'listorder', 'status', 'ctime'], 'integer'],
            [['img'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posidid' => 'Posidid',
            'uid' => 'Uid',
            'img' => 'Img',
            'url' => 'Url',
            'listorder' => 'Listorder',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
