<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 教师管理功能，
 */
class TeacherController extends MBaseController
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
     *用户相关的action集合 
     */
    public function actions()
    {
        return [
        	//显示老师列表
        	'index' => [
        		'class' => 'mis\controllers\teacher\IndexAction',
        	],
        	//搜索用户列表
        	'search' => [
        		'class' => 'mis\controllers\teacher\SearchAction',
        	],
       		//取消老师身份
       		'del' => [
            'class' => 'mis\controllers\teacher\DelAction',
       		],
       		//将用户设置为加v老师
       		'add' => [
            'class' => 'mis\controllers\teacher\AddAction',
       		],
        	//设置为殿堂老师
       		'famousadd' => [
              'class' => 'mis\controllers\teacher\FamousAddAction',
       		],
       		//取消殿堂老师身份
       		'famousdel' => [
              'class' => 'mis\controllers\teacher\FamousDelAction',
       		],
        	//红笔老师列表
       		'redindex' => [
              'class' => 'mis\controllers\teacher\RedIndexAction',
       		],
       		'rededit' => [
              'class' => 'mis\controllers\teacher\RedEditAction',
       		],
       		'reddel' => [
              'class' => 'mis\controllers\teacher\RedDelAction',
       		],
          //改变红笔老师接收批改的状态
          'redstatus' => [
              'class' => 'mis\controllers\teacher\RedStatusAction',
          ],  	
     		//IOS价格选择
          'ios_price_sel' => [
              'class' => 'mis\controllers\teacher\IosPriceSelAction',
          ],
          //付费老师排班
          'pay_teacher_arrange' => [
              'class' => 'mis\controllers\teacher\PayTeacherArrangeAction',
          ], 
          //付费老师排班编辑
          'pay_teacher_arrange_edit' => [
              'class' => 'mis\controllers\teacher\PayTeacherArrangeEditAction',
          ], 
          //付费老师排班删除
          'pay_teacher_arrange_del' => [
              'class' => 'mis\controllers\teacher\PayTeacherArrangeDelAction',
          ],   
          //选择付费批改老师
          'sel_pay_teacher' => [
              'class' => 'mis\controllers\teacher\SelPayTeacherAction',
          ], 

        ];
    }
}