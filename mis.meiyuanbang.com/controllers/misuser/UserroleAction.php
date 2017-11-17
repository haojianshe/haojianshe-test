<?php
namespace mis\controllers\misuser;

use Yii;
use mis\service\MisUserService;
use mis\service\MisRoleService;
use mis\components\MBaseAction;

/**
 * 为用户设置角色
 */
class UserroleAction extends MBaseAction
{
	public $resource_id = 'admin';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	if(!$request->isPost){
    		//判断参数
    		$userid = $request->get('userid');
    		if(!$userid || !is_numeric($userid)){
    			die('非法输入');
    		}    		
    		//得到返回model
    		$ret = $this->getRetModel($userid);
    		return  $this->controller->render('userrole', $ret);
    	}
    	else{
    		//判断用户id
    		$curuserid = $request->post('curuserid');
    		if(!$curuserid || !is_numeric($curuserid)){
    			die('非法输入');
    		}
    		//首先获取用户信息
    		$usermodel = MisUserService::findIdentity($curuserid);
    		//获得用户权限
    		$selected =	$request->post('selected');
    		$roleids = null;
    		if($selected){
    			foreach ($selected as $k=>$v){
    				if($roleids==null){
    					$roleids = $v;
    				}
    				else{
    					$roleids .= ',' . $v;
    				}
    			}
    		}
    		$usermodel->roleids=$roleids;
    		$usermodel->save();
    		$ret = $this->getRetModel($curuserid);
    		$ret['msg'] = '保存成功!';
    		return $this->controller->render('userrole', $ret);
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
