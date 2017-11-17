<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 标签管理
 */
class TagController extends MBaseController
{
	//public $layout = 'frameinner';
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
     * 标签管理Action 集合 
     */
    public function actions()
    {
        return [
        	//分组列表
        	'group_list' => [
        		'class' => 'mis\controllers\tag\GroupListAction',
        	],
        	//分组编辑
        	'group_edit' => [
        		'class' => 'mis\controllers\tag\GroupEditAction',
        	],
       		//标签列表 
        	'tag_list' => [
        		'class' => 'mis\controllers\tag\TagListAction',
        	],
        	//标签编辑
        	'tag_edit' => [
        		'class' => 'mis\controllers\tag\TagEditAction',
        	],
        ];
    }
}