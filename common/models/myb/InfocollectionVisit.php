<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_infocollection_visit".
 *
 * @property string $visitid
 * @property integer $visit_uid
 * @property integer $collection_uid
 * @property integer $subjecttype
 * @property integer $visit_num
 * @property integer $visitdate
 * @property integer $lastvisttime
 * @property integer $ctime
 */
class InfocollectionVisit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_infocollection_visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visit_uid', 'collection_uid', 'subjecttype', 'visit_num', 'visitdate', 'lastvisttime', 'ctime'], 'required'],
            [['visit_uid', 'collection_uid', 'subjecttype', 'visit_num', 'visitdate', 'lastvisttime', 'ctime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'visitid' => 'Visitid',
            'visit_uid' => 'Visit Uid',
            'collection_uid' => 'Collection Uid',
            'subjecttype' => 'Subjecttype',
            'visit_num' => 'Visit Num',
            'visitdate' => 'Visitdate',
            'lastvisttime' => 'Lastvisttime',
            'ctime' => 'Ctime',
        ];
    }
}
