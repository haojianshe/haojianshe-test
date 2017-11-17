<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 系统通知相关接口
 */
class System_messageController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['get','del'],
            ],
		];
	}

    public function actions()
    {
        return [
        	//获取通知
        	'get' => [
        		'class' => 'api\controllers\systemmessage\GetAction',
        	],
        	//删除通知
        	'del'	=> [
       			'class' => 'api\controllers\systemmessage\DelAction',
        	],
		];
    }
}