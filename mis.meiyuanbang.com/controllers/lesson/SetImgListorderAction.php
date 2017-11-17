<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonPicService;

/**
 * 考点删除action
 */
class SetImgListorderAction extends MBaseAction
{	
	public $resource_id = 'operation_lesson';
	
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
    	$listorder = $request->post('listorder');
    	if(!$listorder || !is_numeric($listorder)){
    		die('参数不正确');
    	}
    	$picid = $request->post('picid');
    	if(!$picid || !is_numeric($picid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = LessonPicService::findOne(['picid'=>$picid]);
    	if($model){
    		$model->listorder = $listorder;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    }
}
