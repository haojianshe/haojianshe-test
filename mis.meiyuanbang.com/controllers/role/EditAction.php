<?php
namespace mis\controllers\role;

use Yii;
use mis\service\MisRoleService;
use mis\components\MBaseAction;

/**
 * mis角色添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'admin';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$msg='';
    	$isclose = false;
    	
    	if(!$request->isPost){
    		//get访问，判断是edit还是add,返回不同界面
    		$roleid = $request->get('roleid');   
    		if($roleid){
    			//edit
    			if(!is_numeric($roleid)){
    				die('非法输入');
    			}
    			//根据id取出数据
    			$model = MisRoleService::findOne(['roleid' => $roleid]);
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    		}
    		else{
    			//add
    			$model = new MisRoleService();
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    		}
    	}
    	else{
    		if($request->post('isedit')==1){
    			//update
    			$model =  MisRoleService::findOne(['roleid' => $request->post('MisRoleService')['roleid']]);
    			$model->IsNewRecord = false;
    			$model->load($request->post());
    			$model2 = MisRoleService::findByRolename($model->rolename);
    			if($model2 && $model2->roleid <> $model->roleid)
    			{
    				$msg = '角色名重复';
    				return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    			}    			
    		}
    		else{
    			//insert
    			$model = new MisRoleService();
    			$model->load($request->post());
    			//判断用户名是否重复
    			$model2 = MisRoleService::findByRolename($model->rolename);
    			if($model2)
    			{
    				$msg = '角色名重复';
    				return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    			}
    		}
    		//用户提交
    		if($model->save()){
    			$isclose = true;
    			$msg ='保存成功';
    		}
    		else{
    			$msg ='保存失败';
    		}
    		return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isclose'=>$isclose]);
    	}
    }
}
