<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_recommend_book_adv".
 *
 * @property string $advid
 * @property integer $adv_type
 * @property integer $uid
 * @property integer $bookid
 * @property integer $ctime
 * @property integer $listorder
 * @property integer $status
 */
class RecommendBookAdv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_recommend_book_adv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adv_type', 'uid', 'bookid', 'ctime', 'status'], 'required'],
            [['adv_type', 'uid', 'bookid', 'ctime', 'listorder', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advid' => 'Advid',
            'adv_type' => 'Adv Type',
            'uid' => 'Uid',
            'bookid' => 'Bookid',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
            'status' => 'Status',
        ];
    }
}
