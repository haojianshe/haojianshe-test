<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_favorite_folder".
 *
 * @property integer $folderid
 * @property integer $uid
 * @property string $name
 * @property integer $fav_count
 * @property integer $status
 * @property integer $ctime
 * @property integer $utime
 * @property integer $hits
 */
class FavoriteFolder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_favorite_folder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'fav_count', 'status', 'ctime', 'utime', 'hits'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'folderid' => 'Folderid',
            'uid' => 'Uid',
            'name' => 'Name',
            'fav_count' => 'Fav Count',
            'status' => 'Status',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'hits' => 'Hits',
        ];
    }
}
