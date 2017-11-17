<?php
namespace mis\controllers\znarticle;

use Yii;
use mis\components\MBaseAction;
use mis\service\ZhnArticleService;

/**
 * 正能文章列表页
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_zhn';
	
	public function run()
    {
    	//分页获取正能文章列表
    	$data =  ZhnArticleService::getByPage();
    	return $this->controller->render('index',$data);
    }
}
