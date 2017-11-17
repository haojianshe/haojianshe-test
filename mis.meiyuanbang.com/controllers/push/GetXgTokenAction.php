<?php
namespace mis\controllers\push;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\UserPushService;
/**
 * 获取用户推送token
 */
class GetXgTokenAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_push';
	
	public function run()
    {
        $request = Yii::$app->request;
        $umobile = $request->get('umobile');
        $userinfo=UserService::getByMobile($umobile,1);
        if($userinfo){
            $token_arr=UserPushService::getByUid($userinfo[0]['uid']);
            if($token_arr){
                die(implode("</br>", $token_arr)) ;
            }else{
                die("未获取到token");
            }
        }else{
            die("未找到用户");
        }
    }
}
