<?php
namespace api\modules\v3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 个人中心相关接口
 */
class UserController extends ApiBaseController
{ 
  public function behaviors()
  {
      return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => ['buy_video_list','scan_video_del',"scan_video_list"],
          ],
       	  'token' => [
           		'class' => 'api\components\filters\TokenFilter',
        		  'only' => [''],
      	  ],
      ];
  }

    public function actions()
    {
        return [
      	  	//个人中心直播列表
          	'live_list' => [
        		    'class' => 'api\modules\v3\controllers\user\LiveListAction',
          	],
            //个人中心课程列表
            'course_list' => [
                'class' => 'api\modules\v3\controllers\user\CourseListAction',
            ],
            //最新学习列表
            'scan_video_list' => [
                'class' => 'api\modules\v3\controllers\user\ScanVideoListAction',
            ],
            //最新学习列表
            'scan_video_del' => [
                'class' => 'api\modules\v3\controllers\user\ScanVideoDelAction',
            ],
            //最近购买列表
            'buy_video_list' => [
                'class' => 'api\modules\v3\controllers\user\BuyVideoListAction',
            ],
        ];
    }
}