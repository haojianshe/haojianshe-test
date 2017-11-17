<?php
namespace api\modules\v1_3\controllers;
use api\components\ApiBaseController;

/**
* 小组相关功能
*/
class CmtController extends ApiBaseController
{	
	 public function behaviors()
    {
        return [
            'token' => [
                'class' => 'api\components\filters\TokenFilter',
                'only' => ['newcmt','delcmt'],
            ],
            'black' => [
                'class' => 'api\components\filters\BlackFilter',
                'only' => ['newcmt'],
            ],
        ];
    }
	
	/**
	 *小组相关的action集合
	 */
	public function actions()
	{
		return [			
			//获取小组用户列表
			'get' => [
				'class' => 'api\modules\v1_3\controllers\cmt\GetAction',
			],
			//写评论
			'newcmt' => [
				'class' => 'api\modules\v1_3\controllers\cmt\NewCmtAction',
			],
			//写评论
			'delcmt' => [
				'class' => 'api\modules\v1_3\controllers\cmt\DelCmtAction',
			],
			
			//分享页匿名用户发表评论
			'page_newcmt' => [
				'class' => 'api\modules\v1_3\controllers\cmt\PageNewCmtAction',
			],
			
		];
	}
}