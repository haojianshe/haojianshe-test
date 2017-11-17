<?php
namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeService;
use common\service\DictdataService;

/**
 * 活动添加和修改页面
 */
class EditAction extends MBaseAction
{
	public $resource_id = 'operation_posid';	
	
    public function run()
    {
    	$request = Yii::$app->request;
    	$channelid=$request->get("channelid");
    	if(!$request->isPost){
    		//处理get请求
    		$ret = $this->getHandle();
    	}
    	else{
    		//处理post请求
    		$ret = $this->postHandle();
    	}
        //用于区分首页或素材（专题）
        $ret['channelid']=$channelid;
    	return $this->controller->render('edit', $ret);
    }
    
    /**
     * 处理get访问的情况
     */
    private function getHandle(){
    	$request = Yii::$app->request;
    	
    	//判断参数
    	$posidid = $request->get('posidid');
    	if($posidid){
    		//编辑
    		if(!is_numeric($posidid)){
    			die('非法输入');
    		}
    	}
    	else{
    		//新添加
    		$posidid = 0;
    	}
    	$ret = $this->getRetModel($posidid);
    	return  $ret;
    }
    
    /**
     * 处理post访问的情况
     */
    private function postHandle(){
    	$request = Yii::$app->request;
    	
    	if($request->post('isedit')==1){
    		$posidid = $request->post('PosidHomeService')['posidid'];
    		$model = PosidHomeService::findOne(['posidid'=>$posidid]);
    		$model->load($request->post());
    	}
    	else{
    		//insert
    		$model = new PosidHomeService();
    		$model->load($request->post());
    		$model->ctime = time();
    	}
    	if($model->save()){
    		$ret = $this->getRetModel($model->posidid);
    		$ret['msg'] = '保存成功';
    		$ret['isclose'] = true;
    	}
    	else{
    		$ret = $this->getRetModel($model->posidid);
    		$ret['msg'] = '保存失败';
    		$ret['model'] = $model;
    	}    		
    	return $ret;
    }
   
    /**
     * 根据$posidid获取要返回的model
     * $posidid为0代表新建 不为0则从数据库取数据
     */
    private function getRetModel($posidid){
    	if($posidid == 0){
    		$model =  new PosidHomeService();
    		$model->listorder = PosidHomeService::getMaxListorder()+1;
    		$ret['model'] = $model;
    	}
    	else{
    		$ret['model'] = PosidHomeService::findOne(['posidid'=>$posidid]);
    	}
    	//获取推荐类型数组
    	$typemodel = DictdataService::getPosidHomeType();
    	array_unshift($typemodel,['typeid'=>'','typename'=>'选择推荐类型']);
    	$ret['typemodel'] =  $typemodel;
    	return $ret;
    }
}
