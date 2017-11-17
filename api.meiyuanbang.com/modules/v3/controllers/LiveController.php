<?php
namespace api\modules\v3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 *  直播相关接口
 */
class LiveController extends ApiBaseController
{ 
  public function behaviors()
  {
    return [
       //权限检查过滤器，检查用户是否有权进行操作
        'login' => [
            'class' => 'api\components\filters\LoginFilter',
            'only' => ['',],
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
      	  	//直播列表
          	'list' => [
        		    'class' => 'api\modules\v3\controllers\live\ListAction',
          	],
               //报名接口
          	'insertsign' => [
        		    'class' => 'api\modules\v3\controllers\live\InsertsignAction',
          	],
            //直播详情
            'get_info' => [
                'class' => 'api\modules\v3\controllers\live\GetInfoAction',
            ],
        ];
    }
}