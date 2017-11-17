<?php
namespace mis\controllers\cache;

use Yii;
use mis\components\MBaseAction;

/**
 * 为用户设置角色
 */
class AllAction extends MBaseAction
{
	public $resource_id = 'admin';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	
    	$redis = Yii::$app->cache;
    	$ret = $redis->flushdb();
    	
    	return $this->controller->outputMessage(['errno'=>0]);  	
    }
}
