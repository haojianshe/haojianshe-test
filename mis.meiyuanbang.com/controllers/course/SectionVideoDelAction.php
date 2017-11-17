<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionVideoService;

/**
 * mis用户删除action
 */
class SectionVideoDelAction extends MBaseAction
{	
	public $resource_id = 'operation_course';
	
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
    	$coursevideoid = $request->post('coursevideoid');
    	if(!$coursevideoid || !is_numeric($coursevideoid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CourseSectionVideoService::findOne(['coursevideoid' => $coursevideoid]);
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
