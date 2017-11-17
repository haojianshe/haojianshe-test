<?php
namespace api\modules\v1_3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class LessonController extends ApiBaseController
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
        	//一级分类
        	'maintypelist' => [
        		'class' => 'api\modules\v1_3\controllers\lesson\MainTypeListAction',
        	],
        	//二级分类首页考点
        	'subgetnew'	=> [
       			'class' => 'api\modules\v1_3\controllers\lesson\SubGetNewAction',
        	],
       		//二级分类第二页以后考点
        	'subgetold'	=> [
   				'class' => 'api\modules\v1_3\controllers\lesson\SubGetOldAction',
       		],
       		//根据标题搜索
       		'lessondetail'	=> [
   				'class' => 'api\modules\v1_3\controllers\lesson\LessonDetailAction',
       		],
       		//根据标题搜索
       		'search'	=> [
   				'class' => 'api\modules\v1_3\controllers\lesson\SearchAction',
       		],
        ];
    }
}