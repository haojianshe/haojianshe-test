<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_user_home_profession".
 *
 * @property integer $uid
 * @property integer $professionid
 * @property integer $ctime
 * @property integer $utime
 * @property integer $provinceid
 */
class UserHomeProfession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_user_home_profession';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime', 'utime'], 'required'],
            [['uid', 'professionid', 'ctime', 'utime', 'provinceid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'professionid' => 'Professionid',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'provinceid' => 'Provinceid',
        ];
    }
}
