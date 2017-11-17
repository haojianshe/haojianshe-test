<?php
namespace mis\controllers\resource;

use Yii;
use mis\service\MisResourceService;
use mis\components\MBaseAction;

/**
 * 资源添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'admin';
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$msg='';
    	$isclose = false;
    	$isedit = 0;
    	
    	if(!$request->isPost){
    		//get访问，判断是edit还是add,返回不同界面
    		$resourceid = $request->get('resourceid');   
    		if($resourceid){
    			$isedit = 1;
    			//根据id取出数据
    			$model = MisResourceService::findOne(['resourceid' => $resourceid]);
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isedit'=>$isedit]);
    		}
    		else{
    			//add
    			$model = new MisResourceService();
    			return $this->controller->render('edit', ['model' => $model,'msg'=>$msg]);
    		}
    	}
    	else{
    		if($request->post('isedit')==1){
    			$isedit = 1;
    			//update
    			$model =  MisResourceService::findOne(['resourceid' => $request->post('MisResourceService')['resourceid']]);
    			$model->IsNewRecord = false;
    			$model->load($request->post());
    		}
    		else{
    			//insert
    			$model = new MisResourceService();
    			$model->load($request->post());
    			//判断资源id是否重复
    			$model2 = MisResourceService::findOne(['resourceid' => $request->post('MisResourceService')['resourceid']]);
    			if($model2)
    			{
    				$msg = '资源id重复';
    				return $this->controller->render('edit', ['model' => $model,'msg'=>$msg,'isedit'=>$isedit]);
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
