<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "eci_team_info".
 *
 * @property integer $teamid
 * @property integer $uid
 * @property string $teamname
 * @property integer $membercount
 * @property string $backurl
 * @property string $notice
 * @property integer $noticetime
 * @property string $password
 * @property integer $ctime
 */
class TeamInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eci_team_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'membercount', 'noticetime', 'ctime'], 'integer'],
            [['teamname'], 'string', 'max' => 120],
            [['backurl'], 'string', 'max' => 512],
            [['notice'], 'string', 'max' => 1000],
            [['password'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teamid' => '群id',
            'uid' => '群主id',
            'teamname' => '小组名称',
            'membercount' => '成员数',
            'backurl' => '背景url',
            'notice' => '成员公告',
            'noticetime' => '公告发布时间',
            'password' => '小组的密码，目前与私密老师挂钩，暂时存明文',
            'ctime' => '创建时间',
        ];
    }
}
