<?php
namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;
use mis\service\LessonService;
use mis\service\LessonSectionService;

/**
 * 考点编辑页
 * 从此页可以修改考点基本信息、section基本信息
 */
class DashboardAction extends MBaseAction
{
	public $resource_id = 'operation_lesson';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	//检查参数
    	$lessonid = $request->get('lessonid');
    	if($lessonid){
    		if(!is_numeric($lessonid)){
    			die('非法输入');
    		}
    	}
    	else{
    		die('非法输入');
    	}
    	//获取lesson和sections的信息
    	$lessonmodel = LessonService::findOne(['lessonid'=>$lessonid]);
		$ret['lessonmodel'] = $lessonmodel;
    	$sectionmodels = LessonSectionService::getBylessonId($lessonid);
    	$ret['sectionmodels'] = $sectionmodels;    	
    	return $this->controller->render('dashboard', $ret);    	
    }
}
