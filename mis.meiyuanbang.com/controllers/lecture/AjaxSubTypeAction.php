<?php
namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;

/**
 * ajax获取精讲分类型，用于选择精讲主类型后的二级联动
 */
class AjaxSubTypeAction extends MBaseAction
{
	public $resource_id = 'operation_lecture';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$maintypeid = $request->post('maintypeid');
    	if(!$maintypeid || !is_numeric($maintypeid)){
    		die('参数不正确');
    	}
    	//取分类型
    	$ret = DictdataService::getLectureSubType($maintypeid);
    	return $this->controller->outputMessage(['errno'=>0,'data'=>$ret]);
    }    
}
