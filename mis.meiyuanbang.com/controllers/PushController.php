<?php
namespace mis\controllers;
use Yii;
use mis\components\MBaseController;

/**
 * 推送管理功能
 */
class PushController extends MBaseController
{	
	/**
	 * mis下所有方法的过滤器
	 */
  //去掉csrf验证，不然post请求会被过滤掉
  public $enableCsrfValidation = false;
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
		/*	//权限检查过滤器，检查用户是否有权进行操作
			'permission' => [
				'class' => 'mis\components\filters\PermissionFilter',
			],*/
		];
	}
	
    /**
     *action集合 
     */
    public function actions()
    {
        return [
        	'index' => [
        		'class' => 'mis\controllers\push\IndexAction',
        	],

          'get_xg_token' => [
            'class' => 'mis\controllers\push\GetXgTokenAction',
          ],
           'editwap' => [
          'class' => 'mis\controllers\push\EditWapAction',
          ], 
     	    'editlecture' => [
  				'class' => 'mis\controllers\push\EditLectureAction',
       		], 
          'editlesson' => [
          'class' => 'mis\controllers\push\EditLessonAction',
          ], 
          'editactivites' => [
          'class' => 'mis\controllers\push\EditActivitesAction',
          ], 
          'edithome' => [
          'class' => 'mis\controllers\push\EditHomeAction',
          ], 
          'edittweet' => [
          'class' => 'mis\controllers\push\EditTweetAction',
          ], 
			//直播
          'editlive' => [
          'class' => 'mis\controllers\push\EditLiveAction',
          ],
			//课程
          'editcourse' => [
          'class' => 'mis\controllers\push\EditCourseAction',
          ],
           'editsearch' => [
          'class' => 'mis\controllers\push\EditSearchAction',
          ], 
          'cancel_push' => [
          'class' => 'mis\controllers\push\CancelPushAction',
          ],
        ];
    }
}