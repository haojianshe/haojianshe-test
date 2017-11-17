<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_tag_catalog".
 *
 * @property string $tagcatalogid
 * @property string $tag_catalog_name
 * @property integer $tag_catalog_type
 * @property integer $s_catalog_id
 * @property integer $status
 * @property integer $ctime
 */
class TagCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_tag_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_catalog_type', 's_catalog_id', 'status', 'ctime'], 'integer'],
            [['status', 'ctime'], 'required'],
            [['tag_catalog_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tagcatalogid' => 'Tagcatalogid',
            'tag_catalog_name' => 'Tag Catalog Name',
            'tag_catalog_type' => 'Tag Catalog Type',
            's_catalog_id' => 'S Catalog ID',
            'status' => 'Status',
            'ctime' => 'Ctime',
        ];
    }
}
