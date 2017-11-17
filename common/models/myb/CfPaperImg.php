<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_cf_paper_img".
 *
 * @property string $tiid
 * @property string $paper_url
 * @property integer $score
 * @property string $pic_type
 * @property integer $ctime
 */
class CfPaperImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_cf_paper_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score', 'pic_type'], 'required'],
            [['score', 'ctime'], 'integer'],
            [['paper_url'], 'string', 'max' => 150],
            [['pic_type'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tiid' => 'Tiid',
            'paper_url' => 'Paper Url',
            'score' => 'Score',
            'pic_type' => 'Pic Type',
            'ctime' => 'Ctime',
        ];
    }
}
