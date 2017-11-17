<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_menu".
 *
 * @property string $studiomenuid
 * @property integer $menuid
 * @property integer $uid
 * @property integer $menu_type
 * @property integer $ctime
 * @property integer $listorder
 */
class StudioMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menuid', 'uid', 'menu_type', 'ctime', 'listorder'], 'integer'],
            [['ctime'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'studiomenuid' => 'Studiomenuid',
            'menuid' => 'Menuid',
            'uid' => 'Uid',
            'menu_type' => 'Menu Type',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
        ];
    }
}
