<?php
namespace mis\controllers\misuser;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisUserService;

/**
 * mis用户修改自己密码
 */
class ChgownpwdAction extends MBaseAction
{	
    /**
     * 修改自己的密码
     * 只能更改自己密码，不需要权限，检查登录即可
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	$msg='';
    	//layer图标 1表示成功 2失败
    	$msgicon = 1;
    	//取用户信息
    	$userid = Yii::$app->user->getIdentity()->mis_userid;
    	$model = MisUserService::findOne(['mis_userid' => $userid]);
    	
    	if(!$request->isPost){
    		//get访问直接返回用户信息    		
    		return $this->controller->render('changownpwd', ['model' => $model,'newpwd'=>'','msg'=>$msg]);
    	}
    	else{
    		$userid = Yii::$app->user->getIdentity();
    		$newpwd = $request->post('newpwd');
    		$oldpwd = md5($request->post('oldpwd'));
    		if($model->password==$oldpwd){
    			$model->password = md5($newpwd);
    			$model->save();
    			$msg = '修改密码成功';
    			return $this->controller->render('changownpwd', ['model' => $model,'newpwd'=>'','msg'=>$msg,'msgicon'=>$msgicon]);
    		}
    		else {
    			$msg = '旧密码错误';
    			$msgicon = 2;
    			return $this->controller->render('changownpwd', ['model' => $model,'newpwd'=>$newpwd,'msg'=>$msg,'msgicon'=>$msgicon]);
    		}    		
    	}
    }
}
