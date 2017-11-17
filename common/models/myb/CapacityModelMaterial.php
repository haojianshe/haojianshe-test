<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_capacitymodel_material".
 *
 * @property integer $materialid
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property string $tags
 * @property integer $item_id
 * @property string $picurl
 * @property integer $status
 * @property integer $ctime
 * @property integer $utime
 * @property integer $uid
 * @property string $content
 */
class CapacitymodelMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_capacitymodel_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['f_catalog_id', 's_catalog_id', 'tags', 'item_id', 'picurl', 'ctime', 'uid'], 'required'],
            [['f_catalog_id', 's_catalog_id', 'item_id', 'status', 'ctime', 'utime', 'uid'], 'integer'],
            [['content'], 'string'],
            [['tags', 'picurl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'materialid' => 'Materialid',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'tags' => 'Tags',
            'item_id' => 'Item ID',
            'picurl' => 'Picurl',
            'status' => 'Status',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'uid' => 'Uid',
            'content' => 'Content',
        ];
    }
}
