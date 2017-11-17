<?php
namespace api\modules\v2_2_0\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 专题页接口
 */
class MaterialController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
			//权限检查过滤器，检查用户是否有权进行操作
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
		];
	}

    public function actions()
    {
        return [
            //获取最新
        	'get_new' => [
        		'class' => 'api\modules\v2_2_0\controllers\material\SubjectGetNewAction',
        	],
            //上划获取更多
        	'get_old'	=> [
       			'class' => 'api\modules\v2_2_0\controllers\material\SubjectGetOldAction',
        	],
            //详情
            'detail'   => [
                'class' => 'api\modules\v2_2_0\controllers\material\SubjectDetailAction',
            ],
            //顶部广告
            'top_adv'   => [
                'class' => 'api\modules\v2_2_0\controllers\material\TopAdvAction',
            ]
            
        ];
    }
}