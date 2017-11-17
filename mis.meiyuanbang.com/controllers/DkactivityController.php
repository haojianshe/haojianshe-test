<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * 大咖改画管理
 */
class DkactivityController extends MBaseController
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
            //大咖改画活动列表
            'index' => [
                'class' => 'mis\controllers\dkactivity\IndexAction',
            ],
            //活动编辑
            'edit' => [
                'class' => 'mis\controllers\dkactivity\EditAction',
            ],
            //删除大咖改画
            'del' => [
                'class' => 'mis\controllers\dkactivity\DelAction',
            ],
            //选择批改老师
            'teachersel' => [
                'class' => 'mis\controllers\dkactivity\TeacherSelAction',
            ],
            //大咖改画内容模块编辑
            'editmodule' => [
                'class' => 'mis\controllers\dkactivity\EditModuleAction',
            ],
            //删除大咖改画模块
            'delmodel' => [
                'class' => 'mis\controllers\dkactivity\DelModelAction',
            ],
            //大咖改画模块列表
            'modules' => [
                'class' => 'mis\controllers\dkactivity\ModelsAction',
            ],
            //模块上传图片
            'picupload' => [
                'class' => 'mis\controllers\dkactivity\PicUploadAction',
            ],
             //模块上传图片
            'submitlist' => [
                'class' => 'mis\controllers\dkactivity\SubmitListAction',
            ],
             //模块上传图片
            'editzan' => [
                'class' => 'mis\controllers\dkactivity\EditZanAction',
            ],
             //删除批改记录
            'delcorrect' => [
                'class' => 'mis\controllers\dkactivity\DelCorrectAction',
            ]
        ];
    }
}