<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "dk_correct".
 *
 * @property string $dkcorrectid
 * @property integer $activityid
 * @property string $f_catalog
 * @property integer $f_catalog_id
 * @property string $content
 * @property integer $ctime
 * @property string $source_pic_rid
 * @property string $s_catalog
 * @property integer $s_catalog_id
 * @property integer $submituid
 * @property integer $teacheruid
 * @property integer $zan_num
 * @property integer $correctid
 * @property integer $add_zan_count
 * @property integer $add_zan_time
 */
class DkCorrect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dk_correct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityid', 'ctime', 'source_pic_rid', 'submituid', 'zan_num'], 'required'],
            [['activityid', 'f_catalog_id', 'ctime', 'source_pic_rid', 's_catalog_id', 'submituid', 'teacheruid', 'zan_num', 'correctid', 'add_zan_count', 'add_zan_time'], 'integer'],
            [['content'], 'string'],
            [['f_catalog', 's_catalog'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dkcorrectid' => 'Dkcorrectid',
            'activityid' => 'Activityid',
            'f_catalog' => 'F Catalog',
            'f_catalog_id' => 'F Catalog ID',
            'content' => 'Content',
            'ctime' => 'Ctime',
            'source_pic_rid' => 'Source Pic Rid',
            's_catalog' => 'S Catalog',
            's_catalog_id' => 'S Catalog ID',
            'submituid' => 'Submituid',
            'teacheruid' => 'Teacheruid',
            'zan_num' => 'Zan Num',
            'correctid' => 'Correctid',
            'add_zan_count' => 'Add Zan Count',
            'add_zan_time' => 'Add Zan Time',
        ];
    }
}
