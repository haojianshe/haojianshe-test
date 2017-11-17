<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonService;
use mis\service\LessonSectionService;
use mis\service\LessonDescService;

/**
 * 考点删除action
 */
class PublishAction extends MBaseAction
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
    	$lessonid = $request->post('lessonid');
    	if(!$lessonid || !is_numeric($lessonid)){
    		die('参数不正确');
    	}
    	//取出lessonid下所有section
    	$sectionmodels = LessonSectionService::getBylessonId($lessonid);

        $lessondesccount=LessonDescService::getLessonDescCount($lessonid);
        if($lessondesccount<1){
            return $this->controller->outputMessage(['errno'=>1,'msg'=>'考点至少需要包括一个描述信息']);
        }
    	if(count($sectionmodels)==0){
    		return $this->controller->outputMessage(['errno'=>1,'msg'=>'考点至少需要包括一个节点']);
    	}
    	foreach ($sectionmodels as $model){
    		if($model->piccount==0){
    			return $this->controller->outputMessage(['errno'=>1,'msg'=>'考点每一个节点的图片数不能为0']);
    		}
    	}
    	//根据id取出数据
    	$model = LessonService::findOne(['lessonid' => $lessonid]);
    	if($model){
    		$model->status =0;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
