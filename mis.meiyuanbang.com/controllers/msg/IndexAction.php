<?php
namespace mis\controllers\msg;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;
use mis\service\MisResourceService;

/**
 * 群发站短
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_msg';
	
	public function run()
	{
    	$request = Yii::$app->request;
    	$msg='';
    	$isclose = false;
    	$isedit = 0;
    	
    	if(!$request->isPost){
    		return $this->controller->render('index');
    	}
    }
}
