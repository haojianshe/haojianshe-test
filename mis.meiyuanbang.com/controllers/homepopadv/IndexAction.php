<?php
namespace mis\controllers\homepopadv;

use Yii;
use mis\components\MBaseAction;
use mis\service\HomePopAdvService;
use common\service\DictdataService;

class IndexAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {	

      	$data=HomePopAdvService::getByPage();
      	//var_dump($data['models']);exit;
       	return $this->controller->render('list',$data); 
    }
}
