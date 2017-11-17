<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_lk_paper".
 *
 * @property string $paperid
 * @property string $lkid
 * @property integer $ctime
 * @property string $studio_name
 * @property string $user_name
 * @property string $content
 * @property integer $uid
 * @property integer $total_score
 * @property integer $professionid
 * @property integer $status
 */
class LkPaper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_lk_paper';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lkid', 'uid', 'total_score', 'professionid'], 'required'],
            [['lkid', 'ctime', 'uid', 'total_score', 'professionid', 'status'], 'integer'],
            [['studio_name', 'user_name'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paperid' => 'Paperid',
            'lkid' => 'Lkid',
            'ctime' => 'Ctime',
            'studio_name' => 'Studio Name',
            'user_name' => 'User Name',
            'content' => 'Content',
            'uid' => 'Uid',
            'total_score' => 'Total Score',
            'professionid' => 'Professionid',
            'status' => 'Status',
        ];
    }
}
