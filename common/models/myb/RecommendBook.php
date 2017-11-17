<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_recommend_book".
 *
 * @property string $recid
 * @property integer $bookid
 * @property integer $ctime
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property integer $status
 */
class RecommendBook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_recommend_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookid', 'ctime', 'status'], 'required'],
            [['bookid', 'ctime', 'f_catalog_id', 's_catalog_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recid' => 'Recid',
            'bookid' => 'Bookid',
            'ctime' => 'Ctime',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'status' => 'Status',
        ];
    }
}
