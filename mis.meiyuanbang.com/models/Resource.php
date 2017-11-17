<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "ci_resource".
 *
 * @property string $rid
 * @property string $img
 * @property string $description
 * @property integer $resource_type
 */
class Resource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['img', 'description'], 'string'],
            [['resource_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rid' => 'Rid',
            'img' => '图片',
            'description' => '描述',
            'resource_type' => '0图片 1声音',
        ];
    }
}
