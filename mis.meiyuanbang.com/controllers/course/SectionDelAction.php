<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionService;

/**
 * mis用户删除action
 */
class SectionDelAction extends MBaseAction
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
    	$sectionid = $request->post('sectionid');
    	if(!$sectionid || !is_numeric($sectionid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CourseSectionService::findOne(['sectionid' => $sectionid]);
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
