<?php

namespace common\models\myb;

use Yii;

/**
 * This is th e model class for table "myb_mis_fast_correct".
 *
 * @property integer $newsid
 * @property integer $lecture_level1
 * @property integer $lecture_level2
 * @property integer $status
 */
class FastCorrect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_mis_fast_correct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['correct_teacheruids','activity_name','starttime','endtime'], 'required'],
            [['starttime', 'endtime', 'ctime','is_del'], 'integer'],
            [['activity_name','correct_teacheruids','teacher_avatar','teacher_desc'], 'string', 'max' => 255],
            [['wait_title','start_title','teacher_name'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fastcorrectid' => '活动id',
            'starttime' => '开始时间',
            'endtime' => '结束时间',
            'ctime' => '创建时间',
            'activity_name' => '活动名称',
            'correct_teacheruids' => '批改老师uid逗号隔开',   
            'wait_title'=>'等待批改分享标题', 
            'start_title'=>'正在批改分享标题', 
            'teacher_avatar'=>'老师头像', 
            'teacher_name'=>'老师名称', 
            'teacher_desc'=>'老师简介（分享内容'
        ];
    }
}
