<?php
namespace mis\controllers\cms;

use Yii;
use mis\components\MBaseAction;

/**
 * 老师管理首页，显示老师列表
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_cms';
	
	public function run()
    {
    	//分页获取黑名单用户列表
    	$data =  UserService::getTeacherByPage();
    	return $this->controller->render('index');
    }
}
