<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "eci_user_prize".
 *
 * @property integer $id
 * @property string $is_prize
 * @property integer $uid
 * @property string $code
 * @property string $createTime
 */
class UserPrize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eci_user_prize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['createTime'], 'safe'],
            [['is_prize'], 'string', 'max' => 2],
            [['code'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_prize' => 'Is Prize',
            'uid' => 'Uid',
            'code' => 'Code',
            'createTime' => 'Create Time',
        ];
    }
}
