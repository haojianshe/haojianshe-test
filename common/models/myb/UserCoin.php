<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "eci_user_coin".
 *
 * @property integer $uid
 * @property integer $gradeid
 * @property integer $total_coin
 * @property integer $remain_coin
 */
class UserCoin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eci_user_coin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'gradeid'], 'required'],
            [['uid', 'gradeid', 'total_coin', 'remain_coin'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'gradeid' => 'Gradeid',
            'total_coin' => 'Total Coin',
            'remain_coin' => 'Remain Coin',
        ];
    }
}
