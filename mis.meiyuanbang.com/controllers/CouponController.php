<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 课程券
 */
class CouponController extends MBaseController
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
        	//课程券列表
        	'index' => [
        		'class' => 'mis\controllers\coupon\IndexAction',
        	],
       		//课程券编辑
       		'edit' => [
  				  'class' => 'mis\controllers\coupon\EditAction',
       		],
        	//课程券投放
       		'coupon_grant' => [
   				   'class' => 'mis\controllers\coupon\CouponGrantAction',
       		],
          //课程券用户列表
          'user' => [
              'class' => 'mis\controllers\coupon\UserAction',
          ],
       		//删除
          'del' => [
             'class' => 'mis\controllers\coupon\DelAction',
          ],//删除
       		'user_del' => [
   				   'class' => 'mis\controllers\coupon\UserDelAction',
       		],
          //课程券列表
          'grant' => [
            'class' => 'mis\controllers\coupon\GrantAction',
          ],//课程券列表
          'grant_updatestatus' => [
            'class' => 'mis\controllers\coupon\GrantUpdataStatusAction',
          ], 
          'grant_add' => [
            'class' => 'mis\controllers\coupon\GrantAddAction',
          ]
          ,'grant_user' => [
            'class' => 'mis\controllers\coupon\GrantUserAction',
          ]
        ];
    }
}