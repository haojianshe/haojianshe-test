<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 启动页图片
 */
class StartpageController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//token检查
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
		];
	}

    public function actions()
    {
        return [
        	'get' => [
        		'class' => 'api\controllers\startpage\GetAction',
        	],
        ];
    }
}