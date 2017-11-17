<?php
namespace api\modules\v1_3\controllers;

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
			],
		];
	}

    public function actions()
    {
        return [
        	'get_user_favorite' => [
        		'class' => 'api\modules\v1_3\controllers\favorite\GetUserFavoriteAction',
        	],
        ];
    }
}