<?php
namespace api\modules\v2_4_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 
 */
class TweetController extends ApiBaseController
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
    	  	//详情页推荐
          	'materialrecommend' => [
        		  'class' => 'api\modules\v2_4_2\controllers\tweet\MaterialRecommendAction',
          	],
        ];
    }
}