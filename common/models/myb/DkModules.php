<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_modules".
 *
 * @property integer $modulesid
 * @property integer $activityid
 * @property string $title
 * @property integer $type
 * @property string $content
 * @property integer $status
 */
class DkModules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityid', 'title', 'content'], 'required'],
            [['activityid', 'type', 'status'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'modulesid' => 'Modulesid',
            'activityid' => 'Activityid',
            'title' => 'Title',
            'type' => 'Type',
            'content' => 'Content',
            'status' => 'Status',
        ];
    }
}
