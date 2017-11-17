<?php
namespace mis\controllers\misuser;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisUserService;

/**
 * mis用户删除action
 */
class DelAction extends MBaseAction
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
    	$userid = $request->post('userid');
    	if(!$userid || !is_numeric($userid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = MisUserService::findOne(['mis_userid' => $userid]);
    	if($model){
    		$ret = $model->delete();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
