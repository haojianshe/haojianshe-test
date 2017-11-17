<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\CorrectPayteacherArrangeService;

/**
 * 删除付费批改老师排班
 */
class PayTeacherArrangeDelAction extends MBaseAction
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
    	$arrangeid = $request->post('arrangeid');
    	if(!$arrangeid || !is_numeric($arrangeid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CorrectPayteacherArrangeService::findOne(['arrangeid' => $arrangeid]);
    	if($model){
    		$model->delete();
    		return $this->controller->outputMessage(['errno'=>0]);
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    }
}
