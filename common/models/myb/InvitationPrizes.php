<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_invitation_prizes".
 *
 * @property integer $prizes_id
 * @property string $title
 * @property integer $prizes_type
 * @property string $img
 * @property integer $ctime
 * @property string $username
 * @property integer $number
 * @property integer $status
 */
class InvitationPrizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_invitation_prizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'prizes_type', 'img', 'ctime', 'number'], 'required'],
            [['prizes_type', 'ctime', 'number', 'status'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['img'], 'string', 'max' => 200],
            [['username'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prizes_id' => 'Prizes ID',
            'title' => 'Title',
            'prizes_type' => 'Prizes Type',
            'img' => 'Img',
            'ctime' => 'Ctime',
            'username' => 'Username',
            'number' => 'Number',
            'status' => 'Status',
        ];
    }
}
