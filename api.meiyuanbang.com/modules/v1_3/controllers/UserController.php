<?php
namespace api\modules\v1_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class UserController extends ApiBaseController
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
        	//找画友列表
        	'friendlist' => [
        		'class' => 'api\modules\v1_3\controllers\user\FriendListAction',
        	],
        	//画友搜索
        	'friendsearch'	=> [
       			'class' => 'api\modules\v1_3\controllers\user\FriendSearchAction',
        	],
       		//名师搜索
        	'famoussearch'	=> [
   				'class' => 'api\modules\v1_3\controllers\user\FamousSearchAction',
       		],
       		//名师搜索
       		'hotword'	=> [
   				'class' => 'api\modules\v1_3\controllers\user\HotWordAction',
       		],
          //html 页图片上传
         'upload_html_avatar'  => [
              'class' => 'api\modules\v1_3\controllers\user\UploadHtmlAvatarAction',
          ],
        ];
    }
}