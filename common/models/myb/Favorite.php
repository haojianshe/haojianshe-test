<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "ci_favorite".
 *
 * @property integer $fid
 * @property integer $uid
 * @property integer $tid
 * @property integer $ctime
 * @property integer $type
 * @property integer $folderid
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ci_favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'tid'], 'required'],
            [['uid', 'tid', 'ctime', 'type', 'folderid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fid' => 'Fid',
            'uid' => 'Uid',
            'tid' => 'Tid',
            'ctime' => 'Ctime',
            'type' => 'Type',
            'folderid' => 'Folderid',
        ];
    }
}
