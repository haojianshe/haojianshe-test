<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_address".
 *
 * @property string $addrid
 * @property integer $uid
 * @property integer $ctime
 * @property string $tel
 * @property string $addr_title
 * @property string $addr_detail
 * @property string $addr_img
 * @property string $addr_url
 * @property integer $status
 */
class StudioAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime', 'status'], 'required'],
            [['uid', 'ctime', 'status'], 'integer'],
            [['tel'], 'string', 'max' => 15],
            [['addr_title'], 'string', 'max' => 10],
            [['addr_detail'], 'string', 'max' => 255],
            [['addr_img'], 'string', 'max' => 200],
            [['addr_url'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'addrid' => 'Addrid',
            'uid' => 'Uid',
            'ctime' => 'Ctime',
            'tel' => 'Tel',
            'addr_title' => 'Addr Title',
            'addr_detail' => 'Addr Detail',
            'addr_img' => 'Addr Img',
            'addr_url' => 'Addr Url',
            'status' => 'Status',
        ];
    }
}
