<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvUserService;
class UserAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {
		$data=AdvUserService::getByPage();
       	return $this->controller->render('user',$data); 
    }
}
