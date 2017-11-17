<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 清缓存
 */
class CacheController extends MBaseController
{
	public $enableCsrfValidation = false;
	/**
	 * 过滤器
	 */
	public function behaviors()
	{
		return [
			//检查用户登录
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					// 允许认证用户
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			//权限检查过滤器，检查用户是否有权进行操作
			'permission' => [
				'class' => 'mis\components\filters\PermissionFilter',
			],
		];
	}
	
    /**
     *用户相关的action集合 
     */
    public function actions()
    {
        return [
        	'index' => [
        		'class' => 'mis\controllers\cache\IndexAction',
        	],
        	//清空当前database缓存
        	'all' => [
        		'class' => 'mis\controllers\cache\AllAction',
        	],
        ];
    }
}