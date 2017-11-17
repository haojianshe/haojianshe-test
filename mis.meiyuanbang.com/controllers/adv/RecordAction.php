<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvResourceService;

class RecordAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {	
    	$advuid=$this->requestParam('advuid') ;
      	$data=AdvResourceService::getByPage($advuid);
      	$data['advuid']=$advuid;
       	return $this->controller->render('record',$data); 
    }
}
