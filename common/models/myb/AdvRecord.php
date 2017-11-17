<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_adv_record".
 *
 * @property integer $advrecid
 * @property integer $pos_type
 * @property string $adv_f_catalog_id
 * @property string $adv_s_catalog_id
 * @property string $adv_t_catalog_id
 * @property string $sortid
 * @property integer $stime
 * @property integer $etime
 * @property string $advid
 * @property integer $ctime
 * @property integer $status
 */
class AdvRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_adv_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_type', 'adv_f_catalog_id', 'adv_s_catalog_id', 'adv_t_catalog_id', 'sortid', 'stime', 'etime', 'advid', 'ctime', 'status'], 'integer'],
            [['adv_f_catalog_id', 'adv_s_catalog_id', 'stime', 'etime', 'advid', 'ctime'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advrecid' => 'Advrecid',
            'pos_type' => 'Pos Type',
            'adv_f_catalog_id' => 'Adv F Catalog ID',
            'adv_s_catalog_id' => 'Adv S Catalog ID',
            'adv_t_catalog_id' => 'Adv T Catalog ID',
            'sortid' => 'Sortid',
            'stime' => 'Stime',
            'etime' => 'Etime',
            'advid' => 'Advid',
            'ctime' => 'Ctime',
            'status' => 'Status',
        ];
    }
}
