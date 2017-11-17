<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 版本信息
 */
class VersionController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
		];
	}

    public function actions()
    {
        return [
        	//得到版本信息
        	'get' => [
        		'class' => 'api\controllers\version\GetAction',
        	],
       		//判断是否显示第三方登录
       		'thirdlogindisplay' => [
   				'class' => 'api\controllers\version\ThirdLoginDisplayAction',
       		],
        ];
    }
}