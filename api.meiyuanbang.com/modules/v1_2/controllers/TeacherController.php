<?php
namespace api\modules\v1_2\controllers;
use api\components\ApiBaseController;

/**
* 名师功能
*/
class TeacherController extends ApiBaseController
{	
	public function behaviors()
    {
        return [
            //权限检查过滤器，检查用户是否有权进行操作
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['get_new','get_old'],
            ],
        ];
    }
	
	/**
	 *用户相关的action集合
	 */
	public function actions()
	{
		return [			
			//殿堂分页
			'get_old' => [
					'class' => 'api\modules\v1_2\controllers\teacher\GetOldAction',
			],
			//殿堂最新数据
			'get_new' => [
					'class' => 'api\modules\v1_2\controllers\teacher\GetNewAction',
			],
			
		];
	}   
}