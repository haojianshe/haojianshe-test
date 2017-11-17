<?php
namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeService;
use common\service\DictdataService;

/**
 * 首页推荐位列表页
 */
class MaterialAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_posid';
	
	public function run()
    {
    	//首页
    	$moedls = PosidHomeService::findAll(['status'=>0,"channelid"=>2]);
    	//处理类型
    	foreach ($moedls as $k=>$v){
    	
    		$v['typeid'] = DictdataService::getPosidHomeTypeById($v['typeid'])['typename'];
    		$moedls[$k] = $v;
    	}
    	//die('123');
    	return $this->controller->render('material',['models'=>$moedls]);
    }
}
