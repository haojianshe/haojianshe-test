<?php
namespace mis\controllers\cms;

use Yii;
use mis\components\MBaseAction;

/**
 * mis用户添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_cms';
	
    public function run()
    {
    	return $this->controller->render('edit');
    }
}
