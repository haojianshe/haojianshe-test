<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_adv_resource".
 *
 * @property integer $advid
 * @property integer $channelid
 * @property integer $typeid
 * @property string $topimage1
 * @property string $topimage2
 * @property string $param1
 * @property string $param2
 * @property string $param3
 * @property string $param4
 * @property string $param5
 * @property integer $listorder
 * @property string $desc
 * @property integer $status
 * @property string $title
 * @property string $hits
 * @property string $clickcount
 * @property string $advuid
 * @property integer $ctime
 */
class AdvResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_adv_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channelid', 'typeid', 'listorder', 'status', 'hits', 'clickcount', 'advuid', 'ctime'], 'integer'],
            [['typeid', 'topimage1', 'topimage2', 'advuid', 'ctime'], 'required'],
            [['topimage1', 'topimage2', 'param1', 'param2', 'param3', 'param4', 'param5', 'desc'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advid' => 'Advid',
            'channelid' => 'Channelid',
            'typeid' => 'Typeid',
            'topimage1' => 'Topimage1',
            'topimage2' => 'Topimage2',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'param3' => 'Param3',
            'param4' => 'Param4',
            'param5' => 'Param5',
            'listorder' => 'Listorder',
            'desc' => 'Desc',
            'status' => 'Status',
            'title' => 'Title',
            'hits' => 'Hits',
            'clickcount' => 'Clickcount',
            'advuid' => 'Advuid',
            'ctime' => 'Ctime',
        ];
    }
}
