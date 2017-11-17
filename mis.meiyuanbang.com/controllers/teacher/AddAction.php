<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * mis用户删除action
 */
class AddAction extends MBaseAction
{	
	public $resource_id = 'operation_teacher';
	
    /**
     * 只支持post删除
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$userid = $request->post('userid');
    	if(!$userid || !is_numeric($userid)){
    		die('参数不正确');
    	}
    	//判断用户是否已经是老师
    	$model = UserService::findOne(['uid' => $userid]);
    	if(!$model){
    		die('参数不正确');
    	}
    	if($model->ukind==1){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户已经是认证老师了！']);
    	}    	
    	//设置用户
    	$model->ukind = 1;
    	$ret = $model->save();
    	//清除用户缓存
    	UserService::removecache($userid);
    	if($ret){
    		return $this->controller->outputMessage(['errno'=>0]);
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    	}    	
    }
}
