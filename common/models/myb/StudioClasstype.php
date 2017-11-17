<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_studio_classtype".
 *
 * @property string $classtypeid
 * @property integer $uid
 * @property string $classtype_title
 * @property string $classtype_img
 * @property string $classtype_consultant
 * @property string $class_desc
 * @property string $tel
 * @property integer $classtype_sum
 * @property string $classtype_content
 * @property string $username
 * @property integer $ctime
 * @property integer $listorder
 * @property integer $status
 */
class StudioClasstype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_studio_classtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ctime'], 'required'],
            [['uid', 'classtype_sum', 'ctime', 'listorder', 'status'], 'integer'],
            [['classtype_content'], 'string'],
            [['classtype_title'], 'string', 'max' => 10],
            [['classtype_img'], 'string', 'max' => 200],
            [['classtype_consultant'], 'string', 'max' => 30],
            [['class_desc'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 15],
            [['username'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'classtypeid' => 'Classtypeid',
            'uid' => 'Uid',
            'classtype_title' => 'Classtype Title',
            'classtype_img' => 'Classtype Img',
            'classtype_consultant' => 'Classtype Consultant',
            'class_desc' => 'Class Desc',
            'tel' => 'Tel',
            'classtype_sum' => 'Classtype Sum',
            'classtype_content' => 'Classtype Content',
            'username' => 'Username',
            'ctime' => 'Ctime',
            'listorder' => 'Listorder',
            'status' => 'Status',
        ];
    }
}
