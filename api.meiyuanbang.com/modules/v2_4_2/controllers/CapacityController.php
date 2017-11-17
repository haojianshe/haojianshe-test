<?
namespace api\modules\v2_4_2\controllers;
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
         				'only' => [''],
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
                'class' => 'api\modules\v2_4_2\controllers\capacity\UserCapacityAction',
            ],
            //能力模型素材推荐
            'materialrecommend' => [
                'class' => 'api\modules\v2_4_2\controllers\capacity\MaterialRecommendAction',
            ],
        ];
    }   
    
}
