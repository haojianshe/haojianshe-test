<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 私信相关接口
 */
class MessageController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			'login' => [
				'class' => 'api\components\filters\LoginFilter',
			],
			'black' => [
				'class' => 'api\components\filters\BlackFilter',
				'only' => ['newmsg'], //黑名单用户禁止发私信
			],
		];
	}

    public function actions()
    {
        return [
        	//发私信
        	'newmsg' => [
        		'class' => 'api\controllers\message\NewMsgAction',
        	],
        	//获取两人对话列表
        	'talk'	=> [
       			'class' => 'api\controllers\message\TalkAction',
        	],
			//获取私信列表，包括
       		'usermsg'	=> [
   				'class' => 'api\controllers\message\UserMsgAction',
       		],
       		//获取私信列表
       		'delmsg'	=> [
   				'class' => 'api\controllers\message\DelMsgAction',
       		],
        ];
    }
}