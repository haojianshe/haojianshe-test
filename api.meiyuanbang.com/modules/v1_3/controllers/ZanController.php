<?php
namespace api\modules\v1_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 赞接口
 */
class ZanController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//token检查
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
			//权限检查过滤器，检查用户是否有权进行操作
			'login' => [
					'class' => 'api\components\filters\LoginFilter',
					'only' => ['praiselist'],
			],
		];
	}

    public function actions()
    {
        return [
        	//找画友列表
        	'praiselist' => [
        		'class' => 'api\modules\v1_3\controllers\zan\PraiseListAction',
        	],        	
        ];
    }
}