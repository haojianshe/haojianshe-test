<?php
namespace mis\controllers\misuser;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MisUserService;

/**
 * 后台用户列表页
 * 此action需要admin权限
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'admin';
	
	public function run()
    {
    	//分页获取用户列表
    	$data = MisUserService::getUserByPage();
    	return $this->controller->render('index', $data);
    }
}
