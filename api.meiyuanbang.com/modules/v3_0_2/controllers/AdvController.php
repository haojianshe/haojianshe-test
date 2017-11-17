<?php
namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 广告相关接口
 */
class AdvController extends ApiBaseController
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
      	  	//得到信息
          	'get' => [
        		    'class' => 'api\modules\v3_0_2\controllers\adv\GetAction',
          	],
            
        ];
    }
}