<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_adv_record_place".
 *
 * @property integer $advrecid
 * @property integer $provinceid
 * @property integer $status
 */
class AdvRecordPlace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_adv_record_place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advrecid', 'provinceid'], 'required'],
            [['advrecid', 'provinceid', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advrecid' => 'Advrecid',
            'provinceid' => 'Provinceid',
            'status' => 'Status',
        ];
    }
}
