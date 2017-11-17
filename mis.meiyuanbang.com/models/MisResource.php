<?php

namespace mis\models;

use Yii;

/**
 * This is the model class for table "myb_mis_resource".
 *
 * @property string $resourceid
 * @property string $resourcename
 * @property string $url
 * @property string $desc
 */
class MisResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resourceid', 'resourcename'], 'required'],
            [['resourceid', 'resourcename'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 512],
            [['desc'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resourceid' => '资源编号，比如tweet_manage',
            'resourcename' => '资源名称',
            'url' => ' 资源url地址',
            'desc' => '备注说明',
        ];
    }
}
