<?
namespace api\modules\v2_3_7\controllers;
use api\components\ApiBaseController;
/**
* 能力模型素材
*/
class CapacityController extends ApiBaseController
{
    public function behaviors()
    {
        return [
       		//权限检查过滤器，检查用户是否有权进行操作
       		'login' => [
                'class' => 'api\components\filters\LoginFilter',
                'only' => ['zan_add','zan_cancle'],
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
            //能力模型素材详情
            'material_info' => [
                'class' => 'api\modules\v2_3_7\controllers\capacity\MaterialInfoAction',
            ],
             //能力模型点赞
            'zan_add' => [
                'class' => 'api\modules\v2_3_7\controllers\capacity\ZanAddAction',
            ],
            //能力模型取消点赞
            'zan_cancle' => [
                'class' => 'api\modules\v2_3_7\controllers\capacity\ZanCancleAction',
            ],

            //点赞用户列表
            'zan_list' => [
                'class' => 'api\modules\v2_3_7\controllers\capacity\MaterialZanlListAction',
            ],
       		
        ];
    }   
    
}
