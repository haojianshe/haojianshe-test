<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 用户获取小红点数据接口
 */
class InfoController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
     		'login' => [
				'class' => 'api\components\filters\LoginFilter',
			],
		];
	}

    public function actions()
    {
        return [
        	//注册用户
        	'msgnum' => [
        		'class' => 'api\controllers\info\MsgNumAction',
        	],
        ];
    }
}