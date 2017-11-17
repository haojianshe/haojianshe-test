<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_capacitymodel".
 *
 * @property integer $uid
 * @property integer $catalogid
 * @property integer $marktimes
 * @property integer $score1_totalmark
 * @property integer $score2_totalmark
 * @property integer $score3_totalmark
 * @property integer $score4_totalmark
 * @property integer $score5_totalmark
 * @property integer $score6_totalmark
 * @property integer $score7_totalmark
 * @property integer $last_correct_scatalogid
 */
class CapacityModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_capacitymodel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'catalogid', 'marktimes'], 'required'],
            [['uid', 'catalogid', 'marktimes', 'score1_totalmark', 'score2_totalmark', 'score3_totalmark', 'score4_totalmark', 'score5_totalmark', 'score6_totalmark', 'score7_totalmark', 'last_correct_scatalogid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'catalogid' => 'Catalogid',
            'marktimes' => 'Marktimes',
            'score1_totalmark' => 'Score1 Totalmark',
            'score2_totalmark' => 'Score2 Totalmark',
            'score3_totalmark' => 'Score3 Totalmark',
            'score4_totalmark' => 'Score4 Totalmark',
            'score5_totalmark' => 'Score5 Totalmark',
            'score6_totalmark' => 'Score6 Totalmark',
            'score7_totalmark' => 'Score7 Totalmark',
            'last_correct_scatalogid' => 'Last Correct Scatalogid',
        ];
    }
}
