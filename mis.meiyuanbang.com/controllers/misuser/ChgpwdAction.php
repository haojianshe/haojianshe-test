<?php
namespace mis\controllers\misuser;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisUserService;

/**
 * mis用户删除action
 */
class ChgpwdAction extends MBaseAction
{	
	public $resource_id = 'admin';
	
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
    	if(!$request->post('pwd') || !$request->post('userid')){
    		die('缺少参数');
    	}
    	$userid = $request->post('userid');
    	if(!is_numeric($userid)){
    		die('参数不正确');
    	}
    	$pwd = $request->post('pwd');
    	if(!$pwd){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = MisUserService::findOne(['mis_userid' => $userid]);
    	if($model){
    		//密码md5
    		$model->password = md5($pwd);
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    		else{
    			return $this->controller->outputMessage(['errno'=>1,'msg'=>'保存失败']);
    		}
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'未找到用户']);
    	}
    }
}
