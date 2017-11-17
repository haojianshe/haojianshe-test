<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_live_recommend".
 *
 * @property integer $liverecid
 * @property string $liveid
 * @property string $ctime
 * @property string $sort_id
 */
class LiveRecommend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_live_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['liveid', 'ctime', 'sort_id'], 'required'],
            [['liveid', 'ctime', 'sort_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'liverecid' => 'Liverecid',
            'liveid' => 'Liveid',
            'ctime' => 'Ctime',
            'sort_id' => 'Sort ID',
        ];
    }
}
