<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis首页类
 */
class TweetController extends MBaseController
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
     *用户相关的action集合 
     */
    public function actions()
    {
        return [
        	//显示帖子列表
        	'index' => [
        		'class' => 'mis\controllers\tweet\IndexAction',
        	],
        	//添加或者修改帖子
        	'edit' => [
        		'class' => 'mis\controllers\tweet\EditAction',
        	],
       		//删除用户  更改帖子状态 加精 推荐 
        	'updatestate' => [
        		'class' => 'mis\controllers\tweet\UpdateStateAction',
        	],
        	//获取标签列表
        	'gettags' => [
        		'class' => 'mis\controllers\tweet\GetTagsAction',
        	],
            //获取标签列表
            'import_excel' => [
                'class' => 'mis\controllers\tweet\ImportExcelAction',
            ],
               //
            'select_menu' => [
                'class' => 'mis\controllers\tweet\SelectMenuAction',
            ],
            


        ];
    }
}