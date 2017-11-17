<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseSectionVideoService;

/**
 * 列表页
 */
class SectionVideoAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_course';
	
	public function run()
    {
    	$request = Yii::$app->request;
    	$sectionid = $request->get('sectionid');
        $courseid = $request->get('courseid');
    	//分页列表
    	$data = CourseSectionVideoService::getByPage($sectionid);
    	$data['sectionid']=$sectionid;
        $data['courseid']=$courseid;
    	return $this->controller->render('sectionvideo',$data);
    }
}
