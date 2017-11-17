<?php
namespace api\modules\v2_0_1\controllers;

use Yii;
use api\components\ApiBaseController;

/**
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
		];
	}

    public function actions()
    {
        return [
        	'tweetgetnew' => [
        		'class' => 'api\modules\v2_0_1\controllers\thread\TweetGetNewAction',
        	],
        	'tweetgetold'	=> [
       			'class' => 'api\modules\v2_0_1\controllers\thread\TweetGetOldAction',
        	],
            'correctgetnew'   => [
                'class' => 'api\modules\v2_0_1\controllers\thread\CorrectGetNewAction',
            ],
            'correctgetold'   => [
                'class' => 'api\modules\v2_0_1\controllers\thread\CorrectGetOldAction',
            ],
        ];
    }
}