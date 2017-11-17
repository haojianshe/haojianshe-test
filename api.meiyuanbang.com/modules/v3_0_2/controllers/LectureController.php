<?php
namespace api\modules\v3_0_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 精讲文章专题相关接口
 */
class LectureController extends ApiBaseController
{ 
  public function behaviors()
  {
      return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => [''],
          ],
       	  'token' => [
           		'class' => 'api\components\filters\TokenFilter',
        		 /* 'only' => [''],*/
      	  ],
      ];
  }

    public function actions()
    {
        return [
            //获取分类
            'get_catalog' => [
                'class' => 'api\modules\v3_0_2\controllers\lecture\GetCatalogAction',
            ],
      	  	//精讲及精讲专题列表
          	'list' => [
        		    'class' => 'api\modules\v3_0_2\controllers\lecture\ListAction',
          	],
            //精讲专题详情
            'subject_detail' => [
                'class' => 'api\modules\v3_0_2\controllers\lecture\SubjectDetailAction',
            ],
            //精讲专题搜索
            'search' => [
                'class' => 'api\modules\v3_0_2\controllers\lecture\SearchAction',
            ],
            //精讲文章详情
            'get_info' => [
                'class' => 'api\modules\v3_0_2\controllers\lecture\GetInfoAction',
            ],
        ];
    }
}