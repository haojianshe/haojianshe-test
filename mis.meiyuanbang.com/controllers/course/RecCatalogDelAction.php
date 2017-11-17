<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseRecommendCatalogService;

/**
 * mis用户删除action
 */
class RecCatalogDelAction  extends MBaseAction
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
    	$recommendid = $request->post('recommendid');
    	if(!$recommendid || !is_numeric($recommendid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CourseRecommendCatalogService::findOne(['recommendid' => $recommendid]);
    	if($model){
    		$ret = $model->delete();
            $redis = Yii::$app->cache;
            $redis->delete("course_catalog");
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
