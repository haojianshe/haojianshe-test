<?php
namespace mis\controllers\teacher;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\BlackListService;

class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_blacklist';
	
	public function run()
    {
    	//分页获取黑名单用户列表
    	$data = BlackListService::getByPage();
    	return $this->controller->render('index',$data);
    }
}
