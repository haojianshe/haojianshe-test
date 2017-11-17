<?php
namespace mis\controllers\blacklist;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\BlackListService;

/**
 * 后台用户列表页
 * 此action需要admin权限
 */
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
