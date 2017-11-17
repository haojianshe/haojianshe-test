<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_home_pop_adv".
 *
 * @property integer $advid
 * @property string $title
 * @property string $provinceid
 * @property string $professionid
 * @property integer $btime
 * @property integer $etime
 * @property integer $typeid
 * @property string $topimage
 * @property string $param1
 * @property string $param2
 * @property string $param3
 * @property string $param4
 * @property string $param5
 * @property integer $status
 * @property integer $ctime
 */
class HomePopAdv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_home_pop_adv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'provinceid', 'professionid', 'btime', 'etime', 'typeid', 'topimage', 'ctime'], 'required'],
            [['btime', 'etime', 'typeid', 'status', 'ctime'], 'integer'],
            [['title', 'provinceid', 'professionid'], 'string', 'max' => 255],
            [['topimage', 'param1', 'param2', 'param3', 'param4', 'param5'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advid' => 'Advid',
            'title' => 'Title',
            'provinceid' => 'Provinceid',
            'professionid' => 'Professionid',
            'btime' => 'Btime',
            'etime' => 'Etime',
            'typeid' => 'Typeid',
            'topimage' => 'Topimage',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'param3' => 'Param3',
            'param4' => 'Param4',
            'param5' => 'Param5',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
