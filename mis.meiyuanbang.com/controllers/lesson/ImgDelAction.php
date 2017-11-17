<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonPicService;

/**
 * 考点删除action
 */
class ImgDelAction extends MBaseAction
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
    	$picid = $request->post('picid');
    	if(!$picid || !is_numeric($picid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = LessonPicService::findOne(['picid' => $picid]);
    	if($model){
    		$ret = $model->delete();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
