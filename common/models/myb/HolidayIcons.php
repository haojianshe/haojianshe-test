<?php

namespace common\models\myb;

use Yii;

/**
 * This is the model class for table "myb_holiday_icons".
 *
 * @property integer $iconsid
 * @property string $bottom_nav1_url
 * @property string $bottom_nav2_url
 * @property string $bottom_nav3_url
 * @property string $bottom_nav4_url
 * @property string $bottom_nav5_url
 * @property string $bottom_nav_color
 * @property string $desc
 * @property integer $status
 * @property integer $ctime
 * @property string $home_videosubject
 * @property string $home_tweet
 * @property string $home_lecture
 * @property string $home_lesson
 * @property string $home_live
 * @property string $home_book
 * @property string $home_qa
 * @property string $home_activity
 */
class HolidayIcons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'myb_holiday_icons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bottom_nav1_url', 'bottom_nav2_url', 'bottom_nav3_url', 'bottom_nav4_url', 'bottom_nav5_url', 'bottom_nav_color', 'ctime', 'home_videosubject', 'home_tweet', 'home_lecture', 'home_lesson', 'home_live', 'home_book', 'home_qa', 'home_activity'], 'required'],
            [['status', 'ctime'], 'integer'],
            [['bottom_nav1_url', 'bottom_nav2_url', 'bottom_nav3_url', 'bottom_nav4_url', 'bottom_nav5_url', 'bottom_nav_color', 'desc', 'home_videosubject', 'home_tweet', 'home_lecture', 'home_lesson', 'home_live', 'home_book', 'home_qa', 'home_activity'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iconsid' => 'Iconsid',
            'bottom_nav1_url' => 'Bottom Nav1 Url',
            'bottom_nav2_url' => 'Bottom Nav2 Url',
            'bottom_nav3_url' => 'Bottom Nav3 Url',
            'bottom_nav4_url' => 'Bottom Nav4 Url',
            'bottom_nav5_url' => 'Bottom Nav5 Url',
            'bottom_nav_color' => 'Bottom Nav Color',
            'desc' => 'Desc',
            'status' => 'Status',
            'ctime' => 'Ctime',
            'home_videosubject' => 'Home Videosubject',
            'home_tweet' => 'Home Tweet',
            'home_lecture' => 'Home Lecture',
            'home_lesson' => 'Home Lesson',
            'home_live' => 'Home Live',
            'home_book' => 'Home Book',
            'home_qa' => 'Home Qa',
            'home_activity' => 'Home Activity',
        ];
    }
}
