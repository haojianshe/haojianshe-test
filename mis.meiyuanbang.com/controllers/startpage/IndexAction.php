<?php
namespace mis\controllers\startpage;

use Yii;
use mis\components\MBaseAction;
use mis\service\StartpageService;

/**
 * 启动页信息列表
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_startpage';
	
	public function run()
    {
    	//获取启动页信息
    	$data = StartpageService::getByPage();
    	return $this->controller->render('index',$data);
    }
}
