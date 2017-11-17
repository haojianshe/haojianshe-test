<?php
namespace mis\controllers\blacklist;

use Yii;
use mis\components\MBaseAction;
use mis\service\BlackListService;

/**
 * mis用户删除action
 */
class AddAction extends MBaseAction
{	
	public $resource_id = 'operation_blacklist';
	
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
    	//判断用户是否已经添加过黑名单
    	$model = BlackListService::findOne(['uid' => $userid]);
    	if($model){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'用户已经在黑名单里！']);
    	}
    	//根据id取出数据
    	$model = new BlackListService();
    	$model->uid = $userid;
    	$model->ctime = time();
    	$ret = $model->save();
    	if($ret){
    		return $this->controller->outputMessage(['errno'=>0]);
    	}
    	else{
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    	}    	
    }
}
