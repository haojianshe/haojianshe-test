<?php
namespace mis\models\form;

use Yii;
use yii\base\Model;

/**
 * 登录form表单
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    //验证码
    public $captcha; 
    //自动登录，暂时不使用
    public $rememberMe = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //用户名密码是必填项
            [['username', 'password'], 'required'],
        	//index/captcha是必须的，否则会报找不到类
        	['captcha', 'captcha','captchaAction'=>'index/captcha'],
        ];
    }
}
