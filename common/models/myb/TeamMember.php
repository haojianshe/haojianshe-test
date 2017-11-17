<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "eci_teammember".
 *
 * @property integer $teamid
 * @property integer $uid
 * @property integer $addtime
 * @property integer $isadmin
 */
class TeamMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eci_teammember';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teamid', 'uid'], 'required'],
            [['teamid', 'uid', 'addtime', 'isadmin'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teamid' => 'Teamid',
            'uid' => 'Uid',
            'addtime' => 'Addtime',
            'isadmin' => 'Isadmin',
        ];
    }
}
