<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_turntable_prize_user".
 *
 * @property integer $prizeuserid
 * @property integer $gameid
 * @property integer $uid
 * @property integer $prizesid
 * @property string $mobile
 * @property string $address
 * @property string $name
 * @property string $source_mark
 * @property integer $ctime
 * @property string $QQ
 */
class TurntablePrizeUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_turntable_prize_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gameid', 'uid', 'prizesid', 'ctime'], 'required'],
            [['gameid', 'uid', 'prizesid', 'ctime'], 'integer'],
            [['mobile', 'QQ'], 'string', 'max' => 30],
            [['address', 'source_mark'], 'string', 'max' => 255],
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
            'gameid' => 'Gameid',
            'uid' => 'Uid',
            'prizesid' => 'Prizesid',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'name' => 'Name',
            'source_mark' => 'Source Mark',
            'ctime' => 'Ctime',
            'QQ' => 'Qq',
        ];
    }
}
