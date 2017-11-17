<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_prize_game_prizes".
 *
 * @property string $gameprizesid
 * @property integer $gameid
 * @property integer $prizesid
 * @property integer $num
 * @property integer $probability_start
 * @property integer $probability_end
 * @property integer $sort
 * @property integer $status
 */
class DkPrizeGamePrizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_prize_game_prizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gameid', 'prizesid'], 'required'],
            [['gameid', 'prizesid', 'num', 'probability_start', 'probability_end', 'sort', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gameprizesid' => 'Gameprizesid',
            'gameid' => 'Gameid',
            'prizesid' => 'Prizesid',
            'num' => 'Num',
            'probability_start' => 'Probability Start',
            'probability_end' => 'Probability End',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }
}
