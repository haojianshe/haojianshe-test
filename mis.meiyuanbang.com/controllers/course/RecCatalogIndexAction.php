<?php
namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseRecommendCatalogService;

/**
 * 列表页
 */
class RecCatalogIndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_course';
	
	public function run()
    {
    	//分页列表
    	$data = CourseRecommendCatalogService::getByPage();
    	return $this->controller->render('reccatalogindex',$data);
    }
}
