<?php 
namespace api\service;
use Yii;
use common\models\myb\UserSms;
/**
*  
*/
class UserSmsService extends UserSms
{
    /**
     * 验证验证码
     * @param  [type] $umobile [description]
     * @param  [type] $captcha [description]
     * @param  [type] $type    [description]
     * @return [type]          [description]
     */
   public static function verifyCaptcha($umobile, $captcha, $type) {
        // load sms_model
        //$this->load->model('sms_model');
        $sms_info =UserSmsService::findOne(["mobile"=>$umobile,"verifycode"=>$captcha,"operate"=>$type,"valid"=>1]);
        if(false === $sms_info) {
            return false;
        }

        //验证码非法
        if(is_null($sms_info)) {
            // return ERR_SMS_VERIFYCODE_ILLEGAL;
        }
        //校验验证码是否失效
        $ctime_keep = $sms_info->ctime_keep;
        if(time() > $ctime_keep) {
            // return ERR_SMS_VERIFYCODE_TIMEOUT;
        }
        return true;
    }  
}