<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_tag_group".
 *
 * @property string $taggroupid
 * @property string $tag_group_name
 * @property integer $tag_group_type
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property integer $status
 * @property integer $ctime
 * @property integer $is_display
 */
class TagGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_tag_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_group_type', 'f_catalog_id', 's_catalog_id', 'status', 'ctime', 'is_display'], 'integer'],
            [['status', 'ctime'], 'required'],
            [['tag_group_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taggroupid' => 'Taggroupid',
            'tag_group_name' => 'Tag Group Name',
            'tag_group_type' => 'Tag Group Type',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'status' => 'Status',
            'ctime' => 'Ctime',
            'is_display' => 'Is Display',
        ];
    }
}
