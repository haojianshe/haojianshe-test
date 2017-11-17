<?php
namespace api\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 获取帖子分类信息
 */
class CatalogController extends ApiBaseController
{	
	public function behaviors()
	{
		return [
		];
	}

    public function actions()
    {
        return [
        	//得到分类信息
        	'get' => [
        		  'class' => 'api\controllers\catalog\GetAction',
        	],
       	
        ];
    }
}