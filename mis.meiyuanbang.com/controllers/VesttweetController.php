<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis首页类
 */
class VesttweetController extends MBaseController
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
     *帖子相关 
     */
    public function actions()
    {
        return [
        	//显示帖子列表
        	'index' => [
        		'class' => 'mis\controllers\vesttweet\IndexAction',
        	],
        	//发帖
        	'add' => [
        		'class' => 'mis\controllers\vesttweet\AddAction',
        	],
            //批量传素材
            'addmaterial' => [
                'class' => 'mis\controllers\vesttweet\AddMaterialAction',
            ],
             //批量传素材
            'addmaterialapi' => [
                'class' => 'mis\controllers\vesttweet\AddMaterialApiAction',
            ],
       		//评论列表
        	'comment' => [
        		'class' => 'mis\controllers\vesttweet\CommentAction',
        	],
        	//发评论
        	'newcomment' => [
        		'class' => 'mis\controllers\vesttweet\NewCommentAction',
        	],
           
            //传图片
            'picupload' => [
                'class' => 'mis\controllers\vesttweet\PicUploadAction',
            ],

            

        ];
    }
}