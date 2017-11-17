<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_resource".
 *
 * @property string $rid
 * @property string $img
 * @property string $description
 * @property integer $resource_type
 * @property string $md5_string
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
            [['resource_type'], 'integer'],
            [['md5_string'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rid' => 'Rid',
            'img' => 'Img',
            'description' => 'Description',
            'resource_type' => 'Resource Type',
            'md5_string' => 'Md5 String',
        ];
    }
}
