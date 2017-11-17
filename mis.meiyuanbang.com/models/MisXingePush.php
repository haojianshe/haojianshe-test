<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_xinge_push".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 */
class MisXingePush extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_xinge_push';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 600]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
        ];
    }
}
