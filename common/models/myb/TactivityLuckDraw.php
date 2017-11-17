<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "tactivity_luck_draw".
 *
 * @property integer $lickdrawid
 * @property string $name
 * @property string $mobile
 * @property string $garde
 * @property string $ctime
 * @property string $prize_id
 * @property string $prize_name
 */
class TactivityLuckDraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tactivity_luck_draw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'mobile', 'garde', 'ctime', 'prize_id', 'prize_name'], 'required'],
            [['ctime', 'prize_id'], 'integer'],
            [['name', 'mobile', 'garde'], 'string', 'max' => 50],
            [['prize_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lickdrawid' => 'Lickdrawid',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'garde' => 'Garde',
            'ctime' => 'Ctime',
            'prize_id' => 'Prize ID',
            'prize_name' => 'Prize Name',
        ];
    }
}
