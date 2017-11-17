<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

class AdvController extends MBaseController
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
	
    public function actions()
    {
        return [
        	//广告主列表
        	'user' => [
        		  'class' => 'mis\controllers\adv\UserAction',
        	],
          //广告位总览
          'position' => [
              'class' => 'mis\controllers\adv\PositionAction',
          ],
         
          //广告主广告列表
          'list' => [
              'class' => 'mis\controllers\adv\ListAction',
          ],
          //广告编辑
          'edit' => [
              'class' => 'mis\controllers\adv\EditAction',
          ],
       		 //删除广告
          'del' => [
              'class' => 'mis\controllers\adv\DelAction',
          ],
          //删除广告主
          'user_del' => [
              'class' => 'mis\controllers\adv\UserDelAction',
          ],
          //广告主用户编辑
          'user_edit' => [
              'class' => 'mis\controllers\adv\UserEditAction',
          ],
          //广告投放
          'record_user' => [
              'class' => 'mis\controllers\adv\RecordUserAction',
          ],
           //广告投放
          'record' => [
              'class' => 'mis\controllers\adv\RecordAction',
          ],
          //投放广告
          'record_edit' => [
              'class' => 'mis\controllers\adv\RecordEditAction',
          ],
          //缩略图上传
          'thumbupload' => [
                'class' => 'mis\controllers\adv\ThumbUploadAction',
            ],
        ];
    }
}