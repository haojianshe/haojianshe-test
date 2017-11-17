<?php
namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 视频专题（首页一招）
 */
class VideosubjectController extends ApiBaseController
{ 
  public function behaviors()
  {
      return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => [''],
          ],
       	  'token' => [
           		'class' => 'api\components\filters\TokenFilter',
        		 /* 'only' => [''],*/
      	  ],
      ];
  }

    public function actions()
    {
        return [
      	  	//专题列表
          	'list' => [
        		    'class' => 'api\modules\v3_0_2\controllers\videosubject\ListAction',
          	],
            //专题详情列表
            'detail' => [
                'class' => 'api\modules\v3_0_2\controllers\videosubject\DetailAction',
            ],
           
        ];
    }
}