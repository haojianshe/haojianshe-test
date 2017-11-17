<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionService;

/**
 * 列表页
 */
class SectionAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_course';
	
	public function run()
    { 	$request = Yii::$app->request;
    	$courseid = $request->get('courseid');
    	//分页列表
    	$data = CourseSectionService::getByPage($courseid);
    	$data['courseid']=$courseid;
    	return $this->controller->render('section',$data);
    }
}
