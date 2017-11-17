<?php
namespace api\modules\v1_3\controllers;

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
			//权限检查过滤器，检查用户是否有权进行操作
			'login' => [
				'class' => 'api\components\filters\LoginFilter',
				'only' => ['get','del'],
			],
		];
	}

    public function actions()
    {
        return [
        	//获取通知
        	'get' => [
        		'class' => 'api\modules\v1_3\controllers\systemmessage\GetAction',
        	],
        	//删除通知
        	'del'	=> [
       			'class' => 'api\modules\v1_3\controllers\systemmessage\DelAction',
        	],
		];
    }
}