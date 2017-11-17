<?php
namespace mis\controllers\cache;

use Yii;
use yii\base\Action;
use mis\components\MBaseAction;

/**
 * 清缓存
 */
class IndexAction extends MBaseAction
{
	//权限
	public $resource_id = 'admin';
	
	public function run()
    {
    	return $this->controller->render('index');
    }
}
