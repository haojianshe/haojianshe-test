<?php
namespace console\controllers;

use Yii;

/**
 * 定时任务触发器
 */
class TriggerController extends \yii\console\Controller
{
	/**
     *相关的action集合 
     */
    public function actions()
    {
        return [
        	//精讲文章定时发布，每5分钟启动一次
        	'livemediainfo' => [
        		'class' => 'console\controllers\trigger\LiveMediainfoAction',
        	],
            //机器人帖子定时发布，每5分钟启动一次
            'tweetpublish' => [
                'class' => 'console\controllers\trigger\TweetPublishAction',
            ],

             //机器人点赞定时发布，每5分钟启动一次
            'tweetzanpublish' => [
                'class' => 'console\controllers\trigger\TweetZanPublishAction',
            ],

            //机器人评论定时发布，每5分钟启动一次
            'tweetcommentpublish' => [
                'class' => 'console\controllers\trigger\TweetCommentPublishAction',
            ],
            //大咖改画群发短信，每5分钟启动一次
            'dkpushsms' => [
                'class' => 'console\controllers\trigger\DkPushSmsAction',
            ],
             //大咖改画分配批改老师
            'dkupdateteacher' => [
                'class' => 'console\controllers\trigger\DkUpdateTeacherAction'
            ], 
            //增加点赞数
            'dkcorrectaddzan' => [
                'class' => 'console\controllers\trigger\DkCorrectAddZanAction'
            ],
            //邀请记录 被邀请人
            'userdetection' => [
                'class' => 'console\controllers\trigger\UserDetectionAction'
            ],
            //推送团购通知
            'pushgroupbuymsg' => [
                'class' => 'console\controllers\trigger\PushGroupBuyMsgAction'
            ],
            //预发放课程卷检查程序
            'coupon' => [
                'class' => 'console\controllers\trigger\CouponAction'
            ]
           
        ];
    }
}