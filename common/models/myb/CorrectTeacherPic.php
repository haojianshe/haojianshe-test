<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct_teacher_pic".
 *
 * @property integer $teacher_uid
 * @property string $rid
 * @property integer $folderid
 * @property integer $utime
 * @property integer $ctime
 * @property string $f_catalog_id
 * @property string $s_catalog_id
 */
class CorrectTeacherPic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct_teacher_pic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_uid', 'rid', 'folderid', 'utime', 'ctime', 'f_catalog_id', 's_catalog_id'], 'required'],
            [['teacher_uid', 'rid', 'folderid', 'utime', 'ctime', 'f_catalog_id', 's_catalog_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacher_uid' => 'Teacher Uid',
            'rid' => 'Rid',
            'folderid' => 'Folderid',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
        ];
    }
}
