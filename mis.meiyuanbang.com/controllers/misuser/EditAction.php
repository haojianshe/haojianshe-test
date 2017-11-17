<?php
namespace mis\controllers\misuser;

use Yii;
use mis\service\MisUserService;
use mis\components\MBaseAction;

/**
 * mis用户添加和修改页面
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
    		$userid = $request->get('userid');   
    		if($userid){
    			//edit
    			if(!is_numeric($userid)){
    				die('非法输入');
    			}
    			//根据id取出数据
    			$model = MisUserService::findOne(['mis_userid' => $userid]);
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    		}
    		else{
    			//add
    			$model = new MisUserService();
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    		}
    	}
    	else{
    		if($request->post('isedit')==1){
    			//update
    			$model =  MisUserService::findOne(['mis_userid' => $request->post('MisUserService')['mis_userid']]);
    			$model->IsNewRecord = false;
    			$model->load($request->post());
    			$model2 = MisUserService::findByUsername($model->mis_username);
    			if($model2 && $model2->mis_userid <> $model->mis_userid)
    			{
    				$msg = '用户名重复';
    				return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    			}    			
    		}
    		else{
    			//insert
    			$model = new MisUserService();
    			$model->load($request->post());
    			//判断用户名是否重复
    			$model2 = MisUserService::findByUsername($model->mis_username);
    			if($model2)
    			{
    				$msg = '用户名重复';
    				return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    			}
    			//密码md5加密
    			$model->password = md5($model->password);
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
