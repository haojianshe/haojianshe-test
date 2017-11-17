<?php
namespace api\modules\v3\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 课程相关接口
 */
class CourseController extends ApiBaseController
{ 
  public function behaviors()
  {
      return [
         //权限检查过滤器，检查用户是否有权进行操作
          'login' => [
              'class' => 'api\components\filters\LoginFilter',
              'only' => ['',],
          ],
       	  'token' => [
           		'class' => 'api\components\filters\TokenFilter',
        		  /*'only' => ['list'],*/
      	  ],
      ];
  }

    public function actions()
    {
        return [
      	  	//课程详情
          	'get_info' => [
        		    'class' => 'api\modules\v3\controllers\course\GetInfoAction',
          	],
            //课程首页推荐
            'recommend' => [
                'class' => 'api\modules\v3\controllers\course\RecommendAction',
            ],
            //课程分类
            'catalog' => [
                'class' => 'api\modules\v3\controllers\course\CatalogAction',
            ],
            //课程分类获取数据列表
            'list' => [
                'class' => 'api\modules\v3\controllers\course\ListAction',
            ],
        ];
    }
}