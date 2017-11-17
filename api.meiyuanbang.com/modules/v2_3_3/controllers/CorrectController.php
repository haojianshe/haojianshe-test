<?php
namespace api\modules\v2_3_3\controllers;

use Yii;
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
      'login' => [
        'class' => 'api\components\filters\LoginFilter',
        'only' => ['get_teacher_folder','get_teacher_pic','add_teacher_pic','del_teacher_pic'],
      ],
   	  'token' => [
   		'class' => 'api\components\filters\TokenFilter',
		'only' => ['rankpage','correctrecommend'],
	  ],
    ];
  }

    public function actions()
    {
        return [
        	//获取批改老师个人目录
          	'get_teacher_folder' => [
            	'class' => 'api\modules\v2_3_3\controllers\correct\GetteacherfolderAction',
          	],
          	//获取老师范例图
    	  	'get_teacher_pic' => [
        		'class' => 'api\modules\v2_3_3\controllers\correct\GetteacherpicAction',
          	],
   		  	//添加老师常用范例图
          	'add_teacher_pic' => [
            	'class' => 'api\modules\v2_3_3\controllers\correct\AddteacherpicAction',
          	],
    	  	//删除老师常用范例图
          	'del_teacher_pic' => [
        		'class' => 'api\modules\v2_3_3\controllers\correct\DelteacherpicAction',
          	],
    	  	//分页排行榜接口
          	'rankpage' => [
        		'class' => 'api\modules\v2_3_3\controllers\correct\RankpageAction',
          	],
    	  	//详情页推荐批改接口
          	'correctrecommend' => [
        		'class' => 'api\modules\v2_3_3\controllers\correct\CorrectrecommendAction',
          	],
        ];
    }
}