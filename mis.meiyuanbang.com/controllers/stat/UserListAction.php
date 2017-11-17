<?php
namespace mis\controllers\stat;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

class UserListAction extends MBaseAction
{
  //在配置文件中配置的resource对应的参数名字
  public $resource_id = 'operation_stat';
  public function run()
    {
		$request = Yii::$app->request;
		//手机号 渠道搜索
		$search['mobile']=$request->get("mobile")?$request->get("mobile"):NULL;
		$search['qd']=$request->get("qd")?$request->get("qd"):NULL;
		if($search['mobile'] || $search['qd']){
	    	$data=UserService::getUserListByQdStat($search['mobile'],$search['qd']);
	    }else{
	    	$data['models']=[];
	    }
    	$data['search']=$search;
       	return $this->controller->render('userlist',$data); 
    }
}
