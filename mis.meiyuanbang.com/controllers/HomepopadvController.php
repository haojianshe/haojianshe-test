<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

class HomepopadvController extends MBaseController
{	
	//去掉csrf验证，不然post请求会被过滤掉
	public $enableCsrfValidation = false;
	
	/**
	 * 首页硬广弹窗管理
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
	
    public function actions()
    {
        return [
        	//硬广弹窗列表
        	'index' => [
        		  'class' => 'mis\controllers\homepopadv\IndexAction',
        	],
         
          //硬广弹窗编辑
          'edit' => [
              'class' => 'mis\controllers\homepopadv\EditAction',
          ],
       		
        ];
    }
}