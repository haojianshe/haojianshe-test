<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 取消老师认证
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_teacher';
	
    /**
     * 只支持post
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
    	//根据id取出数据
    	$model = UserService::findOne(['uid' => $userid]);
    	if($model){
    		$model->ukind =0;
    		$ret = $model->save();    		
    		if($ret){
    			//清除缓存
    			UserService::removecache($userid);
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'取消认证成功']);
    }
}
