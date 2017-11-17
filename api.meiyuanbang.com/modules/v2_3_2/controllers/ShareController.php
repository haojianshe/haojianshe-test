<?php
namespace api\modules\v2_3_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class ShareController extends ApiBaseController
{ 
  public function behaviors()
  {
    return [
     //权限检查过滤器，检查用户是否有权进行操作
      'login' => [
        'class' => 'api\components\filters\LoginFilter',
        'only' => ['success'],
      ],
    ];
  }

    public function actions()
    {
        return [
          //分享成功
          'success' => [
            'class' => 'api\modules\v2_3_2\controllers\share\SuccessAction',
          ],     
        ];
    }
}