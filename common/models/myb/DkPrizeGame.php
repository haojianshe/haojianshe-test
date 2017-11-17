<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_prize_game".
 *
 * @property integer $gameid
 * @property string $title
 * @property integer $ctime
 * @property integer $status
 */
class DkPrizeGame extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_prize_game';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'ctime'], 'required'],
            [['ctime', 'status'], 'integer'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gameid' => 'Gameid',
            'title' => 'Title',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
