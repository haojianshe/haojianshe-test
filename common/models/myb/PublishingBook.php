<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_publishing_book".
 *
 * @property string $bookid
 * @property integer $uid
 * @property string $newsid
 * @property integer $ctime
 * @property string $buy_url
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property string $publishing_name
 * @property string $price
 * @property integer $status
 */
class PublishingBook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_publishing_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'newsid', 'ctime', 'price', 'status'], 'required'],
            [['uid', 'newsid', 'ctime', 'f_catalog_id', 's_catalog_id', 'status'], 'integer'],
            [['price'], 'number'],
            [['buy_url'], 'string', 'max' => 255],
            [['publishing_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bookid' => 'Bookid',
            'uid' => 'Uid',
            'newsid' => 'Newsid',
            'ctime' => 'Ctime',
            'buy_url' => 'Buy Url',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'publishing_name' => 'Publishing Name',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }
}
