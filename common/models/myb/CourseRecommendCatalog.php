<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course_recommend_catalog".
 *
 * @property integer $recommendid
 * @property string $f_catalog_id
 * @property string $s_catalog_id
 * @property string $ctime
 * @property string $sort_id
 */
class CourseRecommendCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course_recommend_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['f_catalog_id', 's_catalog_id', 'ctime', 'sort_id'], 'required'],
            [['f_catalog_id', 's_catalog_id', 'ctime', 'sort_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recommendid' => 'Recommendid',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'ctime' => 'Ctime',
            'sort_id' => 'Sort ID',
        ];
    }
}
