<?php
namespace api\modules\v1_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 用户相关接口
 */
class CommentController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//token检查
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
                /*'only' => ['newcmt','delcmt'],*/
			],
           /* 'black' => [
                'class' => 'api\components\filters\BlackFilter',
                'only' => ['newcmt'],
            ],*/
		];
	}

    public function actions()
    {
        return [
        	'tweetcmt' => [
        		'class' => 'api\modules\v1_3\controllers\comment\TweetCmtAction',
        	],
        	'page_newcmt'	=> [
       			'class' => 'api\modules\v1_3\controllers\comment\PageNewCmtAction',
        	],
            'newcmt'   => [
                'class' => 'api\modules\v1_3\controllers\comment\NewCmtAction',
            ],
            'delcmt'   => [
                'class' => 'api\modules\v1_3\controllers\comment\DelCmtAction',
            ],
        ];
    }
}