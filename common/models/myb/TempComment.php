<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "temp_comment".
 *
 * @property integer $id
 * @property integer $temptid
 * @property string $content
 * @property integer $flag
 * @property integer $tid
 */
class TempComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['temptid', 'flag', 'tid'], 'integer'],
            [['flag'], 'required'],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'temptid' => 'Temptid',
            'content' => 'Content',
            'flag' => 'Flag',
            'tid' => 'Tid',
        ];
    }
}
