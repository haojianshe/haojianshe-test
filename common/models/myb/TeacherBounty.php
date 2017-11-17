<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_teacher_bounty".
 *
 * @property integer $teacherbountyid
 * @property integer $teacheruid
 * @property integer $bounty_type
 * @property integer $orderid
 * @property integer $submituid
 * @property integer $subjecttype
 * @property integer $subjectid
 * @property string $bounty_fee
 * @property integer $ctime
 */
class TeacherBounty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_teacher_bounty';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacheruid', 'orderid', 'submituid', 'subjectid', 'bounty_fee', 'ctime'], 'required'],
            [['teacheruid', 'bounty_type', 'orderid', 'submituid', 'subjecttype', 'subjectid', 'ctime'], 'integer'],
            [['bounty_fee'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacherbountyid' => 'Teacherbountyid',
            'teacheruid' => 'Teacheruid',
            'bounty_type' => 'Bounty Type',
            'orderid' => 'Orderid',
            'submituid' => 'Submituid',
            'subjecttype' => 'Subjecttype',
            'subjectid' => 'Subjectid',
            'bounty_fee' => 'Bounty Fee',
            'ctime' => 'Ctime',
        ];
    }
}
