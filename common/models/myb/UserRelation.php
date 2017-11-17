<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_user_relation".
 *
 * @property integer $id
 * @property integer $a_uid
 * @property integer $b_uid
 * @property integer $a_follow_b
 * @property integer $b_follow_a
 * @property integer $friend_type
 * @property integer $need_recommend
 */
class UserRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_user_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a_uid', 'b_uid'], 'required'],
            [['a_uid', 'b_uid', 'a_follow_b', 'b_follow_a', 'friend_type', 'need_recommend'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'a_uid' => 'A Uid',
            'b_uid' => 'B Uid',
            'a_follow_b' => 'A Follow B',
            'b_follow_a' => 'B Follow A',
            'friend_type' => 'Friend Type',
            'need_recommend' => 'Need Recommend',
        ];
    }
}
