<?php
namespace mis\controllers\dkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkModulesService;

/**
 * 删除活动模块
 */
class DelModelAction extends MBaseAction
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
    	$modulesid = $request->post('modulesid');
    	if(!$modulesid || !is_numeric($modulesid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = DkModulesService::findOne(['modulesid' => $modulesid]);
    	if($model){
    		$model->status =2;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
