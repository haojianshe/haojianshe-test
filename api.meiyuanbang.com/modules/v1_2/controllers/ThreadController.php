<?php
namespace api\modules\v1_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 用户相关接口
 */
class ThreadController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//权限检查过滤器，检查用户是否有权进行操作
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
            //权限检查过滤器，检查用户是否有权进行操作
            'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['follow','tweet_new','usertweet'],
            ],
            'black' => [
                'class' => 'api\components\filters\BlackFilter',
                'only' => ['tweet_new'],
            ],
		];
	}

    public function actions()
    {
        return [
        	'get_new' => [
        		'class' => 'api\modules\v1_2\controllers\thread\GetNewAction',
        	],
        	'get_old'	=> [
       			'class' => 'api\modules\v1_2\controllers\thread\GetOldAction',
        	],
            'tweet_new'   => [
                'class' => 'api\modules\v1_2\controllers\thread\TweetNewAction',
            ],
            'follow'   => [
                'class' => 'api\modules\v1_2\controllers\thread\FollowAction',
            ],
            'usertweet'   => [
                'class' => 'api\modules\v1_2\controllers\thread\UserTweetAction',
            ],
            'temptweetupload'   => [
                'class' => 'api\modules\v1_2\controllers\thread\TempTweetUploadAction',
            ],
        ];
    }
}