<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_mis_blacklist".
 *
 * @property integer $uid
 * @property string $desc
 * @property integer $ctime
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_mis_blacklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime'], 'required'],
            [['uid', 'ctime'], 'integer'],
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'desc' => 'Desc',
            'ctime' => 'Ctime',
        ];
    }
}
