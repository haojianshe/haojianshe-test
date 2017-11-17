<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_material_subject".
 *
 * @property integer $subjectid
 * @property string $title
 * @property string $picurl
 * @property string $rids
 * @property integer $hits
 * @property integer $ctime
 * @property integer $status
 * @property integer $stick_date
 * @property integer $subject_typeid
 * @property string $material_desc
 * @property string $cmtcount
 * @property string $supportcount
 */
class MaterialSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_material_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'picurl', 'rids', 'ctime'], 'required'],
            [['hits', 'ctime', 'status', 'stick_date', 'subject_typeid', 'cmtcount', 'supportcount'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['picurl'], 'string', 'max' => 150],
            [['rids'], 'string', 'max' => 3000],
            [['material_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subjectid' => 'Subjectid',
            'title' => 'Title',
            'picurl' => 'Picurl',
            'rids' => 'Rids',
            'hits' => 'Hits',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'stick_date' => 'Stick Date',
            'subject_typeid' => 'Subject Typeid',
            'material_desc' => 'Material Desc',
            'cmtcount' => 'Cmtcount',
            'supportcount' => 'Supportcount',
        ];
    }
}
