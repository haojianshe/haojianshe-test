<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lk_paper_pic".
 *
 * @property string $picid
 * @property string $paperid
 * @property integer $ctime
 * @property integer $zp_type
 * @property string $img_json
 * @property integer $score
 * @property string $level
 * @property integer $status
 */
class LkPaperPic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lk_paper_pic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paperid', 'score'], 'required'],
            [['paperid', 'ctime', 'zp_type', 'score', 'status'], 'integer'],
            [['img_json'], 'string'],
            [['level'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'picid' => 'Picid',
            'paperid' => 'Paperid',
            'ctime' => 'Ctime',
            'zp_type' => 'Zp Type',
            'img_json' => 'Img Json',
            'score' => 'Score',
            'level' => 'Level',
            'status' => 'Status',
        ];
    }
}
