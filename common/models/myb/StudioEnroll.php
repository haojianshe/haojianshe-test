<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_enroll".
 *
 * @property string $enrollid
 * @property integer $classtypeid
 * @property integer $uid
 * @property string $enroll_title
 * @property string $original_price
 * @property string $discount_price
 * @property string $enroll_desc
 * @property integer $ctime
 * @property integer $listorder
 * @property integer $status
 */
class StudioEnroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_enroll';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classtypeid', 'uid', 'ctime'], 'required'],
            [['classtypeid', 'uid', 'ctime', 'listorder', 'status'], 'integer'],
            [['original_price', 'discount_price'], 'number'],
            [['enroll_title'], 'string', 'max' => 100],
            [['enroll_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enrollid' => 'Enrollid',
            'classtypeid' => 'Classtypeid',
            'uid' => 'Uid',
            'enroll_title' => 'Enroll Title',
            'original_price' => 'Original Price',
            'discount_price' => 'Discount Price',
            'enroll_desc' => 'Enroll Desc',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
            'status' => 'Status',
        ];
    }
}
