<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonDescService;

/**
 * 考点描述action
 */
class DescDelAction extends MBaseAction
{	
	public $resource_id = 'operation_lesson';
	
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
    	$lessondescid = $request->post('lessondescid');
    	if(!$lessondescid || !is_numeric($lessondescid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = LessonDescService::findOne(['lessondescid' => $lessondescid]);
    	if($model){
    		$ret = $model->delete();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
