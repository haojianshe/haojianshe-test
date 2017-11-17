<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_tag".
 *
 * @property integer $tagid
 * @property string $rid
 * @property string $tagcontent
 * @property integer $tagtype
 * @property integer $uid
 * @property integer $totalh
 * @property integer $totalw
 * @property integer $tagx
 * @property integer $tagy
 * @property integer $ctime
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rid', 'tagcontent', 'uid', 'totalh', 'totalw', 'tagx', 'tagy', 'ctime'], 'required'],
            [['rid', 'tagtype', 'uid', 'totalh', 'totalw', 'tagx', 'tagy', 'ctime'], 'integer'],
            [['tagcontent'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tagid' => 'Tagid',
            'rid' => 'Rid',
            'tagcontent' => 'Tagcontent',
            'tagtype' => 'Tagtype',
            'uid' => 'Uid',
            'totalh' => 'Totalh',
            'totalw' => 'Totalw',
            'tagx' => 'Tagx',
            'tagy' => 'Tagy',
            'ctime' => 'Ctime',
        ];
    }
}
