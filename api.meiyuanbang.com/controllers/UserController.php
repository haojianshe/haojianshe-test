<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 用户相关接口
 */
class UserController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//权限检查过滤器，检查用户是否有权进行操作
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
				'only' => ['register_user'],
			],
			'login' => [
				'class' => 'api\components\filters\LoginFilter',
				'only' => ['register_user','_check_sname'],
			],
			'black' => [
				'class' => 'api\components\filters\BlackFilter',
				'only' => ['register_user'],
			],
		];
	}

    public function actions()
    {
        return [
        	//注册用户
        	'register_user' => [
        		'class' => 'api\controllers\user\RegisterUserAction',
        	],
        	'_check_sname'	=> [
       			'class' => 'api\controllers\user\CheckSnameAction',
        	],
            'third_part_login'  => [
                'class' => 'api\controllers\user\ThirdPartLoginAction',
            ],
           
        ];
    }
}