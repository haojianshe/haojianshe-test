<?php
namespace mis\controllers\blacklist;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 为用户设置角色
 */
class SearchAction extends MBaseAction
{
	public $resource_id = 'operation_blacklist';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	if(!$request->isPost){
    		$keyword = '';
    		return  $this->controller->render('search',['models'=>[],'keyword'=>$keyword]);
    	}
    	else{
    		//查找用户
    		$keyword = trim($request->post('keyword'));
    		if($keyword==''){
    			die('非法输入');
    		}
    		//根据keyword获取用户列表
    		$models = UserService::getByName($keyword);
    		return  $this->controller->render('search',['models'=>$models,'keyword'=>$keyword]);
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
