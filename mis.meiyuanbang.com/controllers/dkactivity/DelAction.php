<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkActivityService;

/**
 * 删除活动方法
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_activity';
	
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
    	$activityid = $request->post('activityid');
        $status = $request->post('status');
    	if(!$activityid || !is_numeric($activityid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = DkActivityService::findOne(['activityid' => $activityid]);
    	if($model){
    		$model->status =$status;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
