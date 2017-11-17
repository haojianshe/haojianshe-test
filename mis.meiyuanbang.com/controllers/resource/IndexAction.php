<?php
namespace mis\controllers\resource;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MisResourceService;

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
    	//获取所有资源列表    	
    	$models = MisResourceService::getAllOrderByName();
    	return $this->controller->render('index', ['models'=>$models]);
    }
}
