<?php
namespace api\modules\v2_3_2\controllers;

use Yii;
use api\components\ApiBaseController;

/**
 * 跟着画相关接口
 */
class UserController extends ApiBaseController
{ 
  public function behaviors()
  {
    return [
      //权限检查过滤器，检查用户是否有权进行操作
      'token' => [
        'class' => 'api\components\filters\TokenFilter',
        'only' => ['modify_user_info','change_mobile'],
      ],
      'login' => [
        'class' => 'api\components\filters\LoginFilter',
        'only' => ['modify_user_info','change_mobile'],
      ],
    ];
  }

    public function actions()
    {
        return [
          //第三放账号绑定手机号
          'bind_mobile' => [
            'class' => 'api\modules\v2_3_2\controllers\user\BindMobileAction',
          ],
          //第三方登录（返回绑定状态）
          'third_part_login'  => [
            'class' => 'api\modules\v2_3_2\controllers\user\ThirdPartLoginAction',
          ],   
          //注册接口
          'register'  => [
            'class' => 'api\modules\v2_3_2\controllers\user\RegisterAction',
          ],   
          //更改信息接口
          'modify_user_info'  => [
            'class' => 'api\modules\v2_3_2\controllers\user\ModifyUserInfoAction',
          ],  
          //更改手机号
          'change_mobile' => [
            'class' => 'api\modules\v2_3_2\controllers\user\ChangeMobileAction',
          ],         
          
        ];
    }
}