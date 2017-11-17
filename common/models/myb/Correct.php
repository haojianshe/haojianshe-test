<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_correct".
 *
 * @property integer $correctid
 * @property integer $tid
 * @property integer $submituid
 * @property integer $teacheruid
 * @property string $content
 * @property string $majorcmt_id
 * @property string $pointcmt_ids
 * @property integer $source_pic_rid
 * @property integer $correct_pic_rid
 * @property string $example_pics
 * @property integer $rewardnum
 * @property integer $ctime
 * @property integer $correct_time
 * @property integer $status
 * @property integer $score
 * @property string $markdetail
 * @property integer $refuse_reasonid
 * @property integer $f_catalog_id
 * @property integer $s_catalog_id
 * @property string $recommend_courseids
 * @property integer $correct_fee
 * @property integer $pay_status
 */
class Correct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_correct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tid', 'submituid', 'teacheruid', 'source_pic_rid', 'correct_pic_rid', 'rewardnum', 'ctime', 'correct_time', 'status', 'score', 'refuse_reasonid', 'f_catalog_id', 's_catalog_id', 'correct_fee', 'pay_status'], 'integer'],
            [['submituid', 'source_pic_rid', 'ctime', 'status'], 'required'],
            [['content', 'markdetail'], 'string', 'max' => 500],
            [['majorcmt_id'], 'string', 'max' => 20],
            [['pointcmt_ids', 'example_pics', 'recommend_courseids'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'correctid' => 'Correctid',
            'tid' => 'Tid',
            'submituid' => 'Submituid',
            'teacheruid' => 'Teacheruid',
            'content' => 'Content',
            'majorcmt_id' => 'Majorcmt ID',
            'pointcmt_ids' => 'Pointcmt Ids',
            'source_pic_rid' => 'Source Pic Rid',
            'correct_pic_rid' => 'Correct Pic Rid',
            'example_pics' => 'Example Pics',
            'rewardnum' => 'Rewardnum',
            'ctime' => 'Ctime',
            'correct_time' => 'Correct Time',
            'status' => 'Status',
            'score' => 'Score',
            'markdetail' => 'Markdetail',
            'refuse_reasonid' => 'Refuse Reasonid',
            'f_catalog_id' => 'F Catalog ID',
            's_catalog_id' => 'S Catalog ID',
            'recommend_courseids' => 'Recommend Courseids',
            'correct_fee' => 'Correct Fee',
            'pay_status' => 'Pay Status',
        ];
    }
}
