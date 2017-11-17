<?php
namespace api\controllers;

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
			//token检查
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
		];
	}

    public function actions()
    {
        return [
        	'get_new' => [
        		'class' => 'api\controllers\thread\GetNewAction',
        	],
        	'get_old'	=> [
       			'class' => 'api\controllers\thread\GetOldAction',
        	],
        ];
    }
}