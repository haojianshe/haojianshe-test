<?php
namespace mis\controllers\msg;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 搜索用户
 */
class UserSearchAction extends MBaseAction
{
	public $resource_id = 'operation_msg';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	//区分发信人还是收信人
    	if(!$request->isPost){
    		$keyword = '';
    		return  $this->controller->render('search',['models'=>[],'keyword'=>$keyword,'keytype'=>0]);
    	}
    	else{
    		//查找用户
    		$keytype = $request->post('keytype');
    		$keyword = trim($request->post('keyword'));
    		if($keyword=='' ||$keytype=='' || !is_numeric($keytype)){
    			die('非法输入');
    		}
    		//获取用户列表
    		if($keytype==0){
    			$models = UserService::getByName($keyword);
    		}
    		else{
    			$models = UserService::getByMobile($keyword);
    		}    		
    		return  $this->controller->render('search',['models'=>$models,'keyword'=>$keyword,'keytype'=>$keytype]);
    	}
    }
    
    /**
     * 获取返回model
     * 返回用户model，用户身份model
     */
    private function getRetModel($userid){
    	//取出用户信息
    	$usermodel = MisUserService::findIdentity($userid);
    	//获取用户所拥有的角色id列表
    	if($usermodel['roleids']==''){
    		$userroles = [];
    	}
    	else{
    		$userroles = explode(',',$usermodel['roleids']);
    	}
    	//获取所有角色
    	$rolemodel = MisRoleService::getAllOrderByName();
    	//检查用户和角色的对应关系
    	$ret =[];
    	foreach ($rolemodel as $model){
    		$isset = 0;
    		foreach ($userroles as $k=>$v){
    			if($model['roleid']==$v){
    				$isset = 1;
    				continue;
    			}
    		}
    		$ret[] = ['role'=>$model,'isset'=>$isset];
    	}
    	$ret1 = ['models' => $ret,'usermodel'=>$usermodel];
    	return $ret1;
    }
}
