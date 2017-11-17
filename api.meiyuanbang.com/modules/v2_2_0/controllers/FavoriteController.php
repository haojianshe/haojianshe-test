<?php
namespace api\modules\v2_2_0\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 收藏相关接口
 */
class FavoriteController extends ApiBaseController
{	
	 public function behaviors()
  {
    return [
      //登录检查
      'login' => [
        'class' => 'api\components\filters\LoginFilter',
        'only' => ['cancel','add','get_user_favorite'],
      ],
    ];
  }

    public function actions()
    {
        return [
        	'get_user_favorite' => [
        		'class' => 'api\modules\v2_2_0\controllers\favorite\GetUserFavoriteAction',
        	],
        	'add'	=> [
       			'class' => 'api\modules\v2_2_0\controllers\favorite\AddAction',
        	],
       		'cancel'	=> [
   				'class' => 'api\modules\v2_2_0\controllers\favorite\CancelAction',
       		],
        ];
    }
}