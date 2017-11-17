<?php
namespace api\modules\v2_3_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class CorrectController extends ApiBaseController
{ 
  public function behaviors()
  {
    return [
     //权限检查过滤器，检查用户是否有权进行操作
      'login' => [
        'class' => 'api\components\filters\LoginFilter',
        'only' => ['score'],
      ],
    ];
  }

    public function actions()
    {
        return [
          //获取分数
          'score' => [
            'class' => 'api\modules\v2_3_2\controllers\correct\ScoreAction',
          ],
   		  //精彩批改
          'recommend' => [
            'class' => 'api\modules\v2_3_2\controllers\correct\RecommendAction',
          ],
    	  //排行榜加积分
          'rankcoin' => [
        	'class' => 'api\modules\v2_3_2\controllers\correct\RankcoinAction',
          ],
        ];
    }
}