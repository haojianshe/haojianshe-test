<?
namespace api\modules\v2_3_5\controllers;
use api\components\ApiBaseController;
/**
* 
*/
class CapacityController extends ApiBaseController
{
    public function behaviors()
    {
        return [
       		//权限检查过滤器，检查用户是否有权进行操作
       		'login' => [
   				'class' => 'api\components\filters\LoginFilter',
   				'only' => ['usercapacity'],
      		],
            //权限检查过滤器，检查用户是否有权进行操作
			'token' => [
				'class' => 'api\components\filters\TokenFilter',
			],
        ];
    }
   
    public function actions()
    {
        return [
            //获取用户的能力模型和对应数据
            'usercapacity' => [
                'class' => 'api\modules\v2_3_5\controllers\capacity\UserCapacityAction',
            ],
       		//没有能力模型时推荐的数据
       		'nocapacity' => [
   				'class' => 'api\modules\v2_3_5\controllers\capacity\NoCapacityAction',
       		],
        ];
    }   
    
}
