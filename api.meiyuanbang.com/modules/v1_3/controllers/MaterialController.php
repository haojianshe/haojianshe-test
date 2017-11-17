<?php
namespace api\modules\v1_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 素材相关接口
 */
class MaterialController extends ApiBaseController
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
       		//根据一级分类获取所有对应的2级分类
       		'maintypelist' => [
   				'class' => 'api\modules\v1_3\controllers\material\MainTypeListAction',
       		],
        	//根据一级分类获取所有对应的2级分类
        	'subtypelist' => [
        		'class' => 'api\modules\v1_3\controllers\material\SubTypeListAction',
        	],
        	//根据level获取第一页帖子
        	'getnew'	=> [
       			'class' => 'api\modules\v1_3\controllers\material\GetNewAction',
        	],
       		//根据level获取第二页以后帖子
        	'getold'	=> [
   				'class' => 'api\modules\v1_3\controllers\material\GetOldAction',
       		],
       		//根据level获取第二页以后帖子
       		'totalnum'	=> [
   				'class' => 'api\modules\v1_3\controllers\material\TotalNumAction',
       		],
        ];
    }
}