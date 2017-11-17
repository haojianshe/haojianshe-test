<?
namespace api\modules\v2_2_0\controllers;
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
  				'only' => ['refuse','change','del'],
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
            //获取拒批理由列表
       		'refusereason' => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\RefuseReasonAction',
       		],
       		//获取转作品理由列表
       		'changereason' => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\ChangeReasonAction',
       		],
       		//拒批
       		'refuse' => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\RefuseAction',
       		],
       		//转作品
       		'change' => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\ChangeAction',
       		],
       		//学生删除作品
      		'del' => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\DelAction',
       		],
        	//排行榜
	        'rank'=>[
	            'class'=>'api\modules\v2_2_0\controllers\correct\RankAction'
	        ],
        	//改作品顶部广告
            'top_adv'   => [
                'class' => 'api\modules\v2_2_0\controllers\correct\TopAdvAction',
            ],
        	//分享成功后amr转mp3，支持
       		'sharesuccess'   => [
   				'class' => 'api\modules\v2_2_0\controllers\correct\ShareSuccessAction',
       		]
        ];
    }   
    
}
