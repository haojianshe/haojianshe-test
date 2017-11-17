<?php
namespace mis\controllers\adv;

use Yii;
use mis\components\MBaseAction;
use mis\service\AdvResourceService;
use common\service\DictdataService;

class ListAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_adv';
  public function run()
    {	
    	  $advuid=$this->requestParam('advuid') ;
      	$data=AdvResourceService::getByPage($advuid);
      	$data['advuid']=$advuid;
        //处理类型
        foreach ($data['models'] as $k=>$v){
          $v['typeid'] = DictdataService::getPosidHomeTypeById($v['typeid'])['typename'];
          $data['models'][$k] = $v;
        }
      	//var_dump($data['models']);exit;
       	return $this->controller->render('list',$data); 
    }
}
