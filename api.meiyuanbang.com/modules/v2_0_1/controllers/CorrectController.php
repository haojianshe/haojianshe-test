<?php
namespace api\modules\v2_0_1\controllers;
use api\components\ApiBaseController;
/**
* 
*/
class CorrectController extends ApiBaseController
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
            //上传图片
            'catalogget' => [
                'class' => 'api\modules\v2_0_1\controllers\correct\CatalogGetAction',
            ],
       		//推荐批改老师
       		'teacherrecommend' => [
   				'class' => 'api\modules\v2_0_1\controllers\correct\TeacherRecommendAction',
       		],
        ];
    }   
    
}
