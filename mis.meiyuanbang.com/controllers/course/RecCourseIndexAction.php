<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseRecommendService;

/**
 * 列表页
 */
class RecCourseIndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_course';
	
	public function run()
    {
    	$request = Yii::$app->request;
    	$recommendid = $request->get('recommendid');
    	//分页列表
    	$data = CourseRecommendService::getByPage($recommendid);
    	$data['recommendid']=$recommendid;
    	return $this->controller->render('reccourseindex',$data);
    }
}
