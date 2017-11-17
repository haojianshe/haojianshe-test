<?php
namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;

/**
 * 
 */
class AuditAction extends MBaseAction
{	
	public $resource_id = 'operation_lecture';
	
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
    	$newsid = $request->post('newsid');
    	if(!$newsid || !is_numeric($newsid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = LectureService::findOne(['newsid' => $newsid]);
    	if($model){
    		$model->status = 0;
    		//如果发布时间<现在时间，则文章的时间变为审核时间，否则继续定时
    		if($model->publishtime<time()){
    			$model->publishtime = time();
    		}    		
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    }
}
