<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_user_vest".
 *
 * @property integer $mis_userid
 * @property string $uids
 */
class MisUserVest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_user_vest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mis_userid'], 'required'],
            [['mis_userid'], 'integer'],
            [['uids'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mis_userid' => 'Mis Userid',
            'uids' => 'Uids',
        ];
    }
}
