<?php
namespace mis\controllers\index;

use Yii;
use yii\base\Action;

/**
 * 退出登录action 
 * 退出后跳转到登录页
 */
class LogoutAction extends Action
{
    public function run()
    {
    	Yii::$app->user->logout();    	
    	return $this->controller->redirect('/');
    }
}
