<?php
namespace mis\controllers;

use Yii;
use yii\filters\AccessControl;
use mis\components\MBaseController;
use mis\components\filters\PermissionFilter;
/**
 * mis首页类
 */
class MainController extends MBaseController
{
	public $layout=false;
	
	/**
	 * mis下所有方法的过滤器
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
     * mis首页的所有action方法
     */
    public function actions()
    {
        return [
        	//框架页面
        	'index' => [
        		'class' => 'mis\controllers\main\IndexAction',
        	],
       		//欢迎页
       		'default' => [
       			'class' => 'mis\controllers\main\DefaultAction',
       		],
        ];
    }
}
