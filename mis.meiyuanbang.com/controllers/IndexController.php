<?php
namespace mis\controllers;

use Yii;
use mis\components\MBaseController;

/**
 * mis首页类
 */
class IndexController extends MBaseController
{
	public $layout=false;
	
    /**
     *用户相关的action集合 
     */
    public function actions()
    {
        return [
        	//验证码
        	'captcha' => [
        		'class' => 'common\components\MycaptchaAction',
        		'maxLength' => 4,
        		'minLength' => 4
        	],
        	//用户登录
        	'index' => [
        		'class' => 'mis\controllers\index\LoginAction',
        	],
        	//退出登录
        	'logout' => [
        		'class' => 'mis\controllers\index\LogoutAction',
        	],
        ];
    }
    
    public function actionTest()
    {
    	//die(phpinfo());
    }
}