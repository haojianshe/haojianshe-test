<?php
namespace api\modules\v2_3_2\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserSmsService;
use api\service\UserService;

/**
 *  
 * 更改绑定手机号
 */
class ChangeMobileAction extends ApiBaseAction
{
	public function run()
    {
        $request=Yii::$app->request;
        //加入时间
        $password=$this->requestParam('password',true);
        $umobile=$this->requestParam('umobile');
        $captcha=$this->requestParam('captcha');
        $uid=$this->_uid;

        $user=UserService::findOne(['id'=>$uid,"register_status"=>0]);
        if($user->pass_word !=$password){
            //密码错误
            $this->controller->renderJson(ReturnCodeEnum::USER_ERR_PASS);
        }else{
            if(empty($umobile)){
                 $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
            }
        }
        //查找手机号状态
        $mobile_user=UserService::findOne(['umobile'=>$umobile,"register_status"=>0]);
        if($mobile_user){
            //手机号已注册用户
            $this->controller->renderJson(ReturnCodeEnum::USER_EXIST);
        }
       

        //验证验证码 "operate"=>4是验证码类型  修改手机号
        $sms_info=UserSmsService::find()->where(["mobile"=>$umobile,"verifycode"=>$captcha,"operate"=>4,"valid"=>1])->orderBy("ctime desc")->one();
        //验证是否存在
        if(empty($sms_info)){
            $this->controller->renderJson(ReturnCodeEnum::ERR_SMS_VERIFYCODE_ILLEGAL);
        }
        //验证超时
        $ctime_keep = $sms_info->ctime_keep;
        if(time() > $ctime_keep) {
            $this->controller->renderJson(ReturnCodeEnum::ERR_SMS_VERIFYCODE_TIMEOUT); 
        }
        $user->umobile=$umobile;
        $user->save();
        //验证码失效
        $sms_info->valid=0;
        $sms_info->save();

        $user_detail_info=UserDetailService::getByUid($uid);
       // $user_detail_info["token"]=UserTokenService::createToken($uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$user_detail_info);
    }  

}
