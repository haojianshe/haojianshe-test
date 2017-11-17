<?php
namespace mis\controllers\role;

use Yii;
use mis\service\MisRoleService;
use mis\service\MisRoleResourceService;
use mis\service\MisResourceService;
use mis\components\MBaseAction;

/**
 * 用户角色编辑权限页面
 */
class SetpermissionAction extends MBaseAction
{
	public $resource_id = 'admin';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	
    	if(!$request->isPost){
    		//判断参数
    		$roleid = $request->get('roleid');
    		if(!$roleid || !is_numeric($roleid)){
    			die('非法输入');
    		}    		
    		//得到返回model
    		$ret = $this->getRetModel($roleid);
    		return  $this->controller->render('setpermission', $ret);
    	}
    	else{
    		//判断用户id
    		$curroleid = $request->post('curroleid');
    		if(!$curroleid || !is_numeric($curroleid)){
    			die('非法输入');
    		}
    		//首先删除所有用户的权限
    		MisRoleResourceService::deleteAll('roleid=:roleid',[':roleid'=>$curroleid]);
    		//写用户提交的权限
    		$selected =	$request->post('selected');
    		if($selected){
    			foreach ($selected as $k=>$v){
    				$newmodel = new MisRoleResourceService();
    				$newmodel->roleid = $curroleid;
    				$newmodel->resourceid =$v;
    				$newmodel->save();
    			}
    		}
    		$ret = $this->getRetModel($curroleid);
    		$ret['msg'] = '保存成功!';
    		return $this->controller->render('setpermission', $ret);
    	}
    }
    
    /**
     * 获取返回model
     * 遍历所有资源，添加用户是否已授权标志
     * $resourcemodel 所有资源列表
     * $roleresourceModel 用户已授权资源列表
     */
    private function getRetModel($roleid){
    	//取出用户角色信息
    	$rolemodel = MisRoleService::findOne(['roleid' => $roleid]);
    	//获取用户所拥有的权限列表
    	$roleresourceModel = MisRoleResourceService::getAllByRoleid($roleid);
    	//获取所有权限
    	$resourcemodel = MisResourceService::getAllOrderByName();
    	//检查用户对每个资源是否拥有权限
    	$ret =[];
    	foreach ($resourcemodel as $model){
    		$ispermission = 0;
    		foreach ($roleresourceModel as $model1){
    			if($model['resourceid']==$model1['resourceid']){
    				$ispermission = 1;
    				continue;
    			}
    		}
    		$ret[] = ['resource'=>$model,'ispermission'=>$ispermission];
    	}
    	$ret1 = ['models' => $ret,'rolemodel'=>$rolemodel];
    	return $ret1;
    }
}
