<?php

namespace api\modules\v3_0_4\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class LessonController extends ApiBaseController {

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
    public function actions() {
        return [
            //得到跟着画主分类
            'get_main_type' => [
                'class' => 'api\modules\v3_0_4\controllers\lesson\GetMainTypeAction',
            ],
            //得到跟着画二级分类
            'get_sub_type' => [
                'class' => 'api\modules\v3_0_4\controllers\lesson\GetSubTypeAction',
            ],
            //获取分类推荐内容
            'catalog_list_recommend' => [
                'class' => 'api\modules\v3_0_4\controllers\lesson\CatalogListRecommendAction',
            ],            
            //获取分类推荐内容
            'list' => [
                'class' => 'api\modules\v3_0_4\controllers\lesson\ListAction',
            ],
            /* //获取跟着画详情
            'lessondetail' => [
                'class' => 'api\modules\v3_0_4\controllers\lesson\LessonDetailAction',
            ],*/
        ];
    }

}
