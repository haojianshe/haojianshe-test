<?php
namespace api\modules\v1_3\controllers;

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
				'only' => ['talk'],
			],
		];
	}

    public function actions()
    {
        return [
        	//获取两人对话列表
        	'talk'	=> [
       			'class' => 'api\modules\v1_3\controllers\message\TalkAction',
        	],
			//获取私信列表
       		'usermsg'	=> [
   				'class' => 'api\modules\v1_3\controllers\message\UserMsgAction',
       		],
        ];
    }
}