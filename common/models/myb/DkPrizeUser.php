<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_prize_user".
 *
 * @property integer $prizeuserid
 * @property integer $activityid
 * @property integer $uid
 * @property integer $prizesid
 * @property string $mobile
 * @property string $address
 * @property string $name
 * @property integer $ctime
 */
class DkPrizeUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_prize_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityid', 'uid', 'prizesid', 'ctime'], 'required'],
            [['activityid', 'uid', 'prizesid', 'ctime'], 'integer'],
            [['mobile'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prizeuserid' => 'Prizeuserid',
            'activityid' => 'Activityid',
            'uid' => 'Uid',
            'prizesid' => 'Prizesid',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'name' => 'Name',
            'ctime' => 'Ctime',
        ];
    }
}
