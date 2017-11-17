<?php
namespace api\modules\v3_0_2\controllers;

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
      	  	//首页信息
          	'correct' => [
        		    'class' => 'api\modules\v3_0_2\controllers\user\CorrectAction',
          	],
           
        ];
    }
}