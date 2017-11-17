<?php
namespace mis\controllers\index;

use Yii;
use yii\base\Action;

use mis\service\MisUserService;
use mis\models\form\LoginForm;
/**
 * 用户登录action 
 * 如果用户已经登录则进入mis工作页面
 * 如果未登录则进行登录操作
 */
class LoginAction extends Action
{
    /**
     * 用户登录action的入口
     * 用户的所有验证都可以放到model的rules去做
     * 此处代码用户名是否存在和密码是否正确未放到model中去用rules触发
     */
    public function run()
    {
    	//判断用户登录,已经登录则进入mis首页
    	if (!\Yii::$app->user->isGuest) {
    		return $this->controller->redirect('/main');
    	}
    	//登录操作
    	$model = new LoginForm();
    	if ($model->load(Yii::$app->request->post())) {
    		//数据验证
    		if(!$model->validate()){ 
    			//未通过验证
    			$errs = $model->getFirstErrors();
    			if(isset($errs['captcha'])){
    				return $this->controller->render('index', ['model' => $model,'msg'=>'验证码错误!']);
    			}
    			//除验证码外的其他问题都是非法访问
    			die('非法输入!');
    		}
    		//验证用户登录
    		$identifymodel = MisUserService::findByUsername($model->username);
    		//检查用户名
    		if(!$identifymodel){
    			return $this->controller->render('index', ['model' => $model,'msg'=>'用户不存在!']);
    		}
    		//检查密码   		
    		if(!$identifymodel->validatePassword($model->password)){
    			return $this->controller->render('index', ['model' => $model,'msg'=>'密码错误!']);
    		}
    		//登录成功后调用yii的login保存登录状态，mis不提供自动登录功能
    		Yii::$app->user->login($identifymodel, 0);
    		//跳转到mis首页
    		return $this->controller->redirect('/main');
    	} else {
    		//用户get访问登录页面，直接进入登录页
    		return $this->controller->render('index', ['model' => $model,'msg'=>'']);
    	}
    }
}
