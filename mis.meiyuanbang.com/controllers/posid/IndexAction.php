<?php
namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeService;
use common\service\DictdataService;

/**
 * 首页推荐位列表页
 */
class IndexAction extends MBaseAction
{
	//在配置文件中配置的resource对应的参数名字
	public $resource_id = 'operation_posid';
	
	public function run()
    {
        $request = Yii::$app->request;
        $channelid=$request->get('channelid')?$request->get('channelid'):1;
    	//首页
    	$moedls = PosidHomeService::findAll(['status'=>0,"channelid"=>$channelid]);
    	//处理类型
    	foreach ($moedls as $k=>$v){
    	
    		$v['typeid'] = DictdataService::getPosidHomeTypeById($v['typeid'])['typename'];
    		$moedls[$k] = $v;
    	}
    	//die('123');
    	return $this->controller->render('index',['models'=>$moedls,"channelid"=>$channelid]);
    }
}
