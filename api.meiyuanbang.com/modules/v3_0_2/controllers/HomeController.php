<?php
namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 首页相关接口
 */
class HomeController extends ApiBaseController
{ 
  public function behaviors()
  {
      return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => ['set_homeprofession'],
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
          	'index' => [
        		    'class' => 'api\modules\v3_0_2\controllers\home\IndexAction',
          	],
            //获取首页角色信息
            'set_homeprofession' => [
                'class' => 'api\modules\v3_0_2\controllers\home\SetHomeProfessionAction',
            ],
            //获取首页角色信息
            'get_homeprofession' => [
                'class' => 'api\modules\v3_0_2\controllers\home\GetHomeProfessionAction',
            ],
        ];
    }
}