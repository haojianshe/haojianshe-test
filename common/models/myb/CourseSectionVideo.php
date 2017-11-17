<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_course_section_video".
 *
 * @property integer $coursevideoid
 * @property integer $sectionid
 * @property integer $section_video_num
 * @property string $title
 * @property string $videoid
 * @property string $price
 * @property string $sale_price
 * @property integer $ctime
 * @property integer $status
 * @property integer $courseid
 * @property string $ios_price
 * @property string $bounty_fee_ios
 * @property string $bounty_fee
 */
class CourseSectionVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_course_section_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sectionid', 'section_video_num', 'title', 'videoid', 'ctime', 'courseid'], 'required'],
            [['sectionid', 'section_video_num', 'ctime', 'status', 'courseid'], 'integer'],
            [['price', 'sale_price', 'ios_price', 'bounty_fee_ios', 'bounty_fee'], 'number'],
            [['title', 'videoid'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coursevideoid' => 'Coursevideoid',
            'sectionid' => 'Sectionid',
            'section_video_num' => 'Section Video Num',
            'title' => 'Title',
            'videoid' => 'Videoid',
            'price' => 'Price',
            'sale_price' => 'Sale Price',
            'ctime' => 'Ctime',
            'status' => 'Status',
            'courseid' => 'Courseid',
            'ios_price' => 'Ios Price',
            'bounty_fee_ios' => 'Bounty Fee Ios',
            'bounty_fee' => 'Bounty Fee',
        ];
    }
}
