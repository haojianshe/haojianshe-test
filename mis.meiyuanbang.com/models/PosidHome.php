<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_posid_home".
 *
 * @property integer $posidid
 * @property integer $typeid
 * @property string $topimage
 * @property string $param1
 * @property string $param2
 * @property string $param3
 * @property string $param4
 * @property string $param5
 * @property integer $listorder
 * @property string $desc
 * @property integer $status
 * @property integer $ctime
 */
class PosidHome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_posid_home';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channelid', 'typeid', 'listorder', 'status', 'ctime'], 'integer'],
            [['typeid', 'topimage', 'ctime'], 'required'],
            [['topimage', 'param1', 'param2', 'param3', 'param4', 'param5', 'desc'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posidid' => 'Posidid',
            'channelid' => 'Channelid',
            'typeid' => 'Typeid',
            'topimage' => 'Topimage',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'param3' => 'Param3',
            'param4' => 'Param4',
            'param5' => 'Param5',
            'listorder' => 'Listorder',
            'desc' => 'Desc',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
