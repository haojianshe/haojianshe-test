<?php

namespace common\models\yj;

use Yii;

/**
 * This is the model class for table "yj_user".
 *
 * @property integer $uid
 * @property string $umobile
 * @property string $user_name
 * @property string $user_age
 * @property string $user_address
 * @property string $sign_type
 * @property integer $create_time
 * @property string $expe_time
 * @property string $sign_time
 * @property integer $is_expe
 * @property integer $is_sign
 * @property integer $status
 * @property string $mark
 */
class YjUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yj_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'expe_time', 'sign_time', 'is_expe', 'is_sign', 'status'], 'integer'],
            [['mark'], 'string'],
            [['umobile'], 'string', 'max' => 11],
            [['user_name', 'user_age', 'user_address', 'sign_type'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'umobile' => 'Umobile',
            'user_name' => 'User Name',
            'user_age' => 'User Age',
            'user_address' => 'User Address',
            'sign_type' => 'Sign Type',
            'create_time' => 'Create Time',
            'expe_time' => 'Expe Time',
            'sign_time' => 'Sign Time',
            'is_expe' => 'Is Expe',
            'is_sign' => 'Is Sign',
            'status' => 'Status',
            'mark' => 'Mark',
        ];
    }
}
