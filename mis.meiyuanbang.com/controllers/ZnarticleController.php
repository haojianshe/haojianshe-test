<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 精讲管理功能
 */
class ZnarticleController extends MBaseController
{	
	//去掉csrf验证，不然post请求会被过滤掉
	public $enableCsrfValidation = false;
	
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
     *action集合 
     */
    public function actions()
    {
        return [
        	//正能文章列表
        	'index' => [
        		'class' => 'mis\controllers\znarticle\IndexAction',
        	],
       		//添加或者修改精讲
       		'edit' => [
  				'class' => 'mis\controllers\znarticle\EditAction',
       		],
        	//审核
       		'audit' => [
   				'class' => 'mis\controllers\znarticle\AuditAction',
       		],
       		//删除
       		'del' => [
   				'class' => 'mis\controllers\znarticle\DelAction',
       		],
        ];
    }
}