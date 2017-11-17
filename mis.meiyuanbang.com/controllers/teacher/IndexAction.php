<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 老师管理首页，显示老师列表
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_teacher';
	
	public function run()
    {
    	//分页获取黑名单用户列表
    	$data =  UserService::getTeacherByPage('','',1);
    	return $this->controller->render('index',$data);
    }
}
