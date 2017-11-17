<?php
namespace mis\controllers\capacity;

use Yii;
use mis\components\MBaseAction;
use mis\service\CapacityModelMaterialService;

/**
 *删除能力模型素材
 */
class DelAction extends MBaseAction
{ 
    /**
     * 只支持post删除
     */
    public function run()
    {  
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$materialid = $request->post('materialid');
    	if(!$materialid || !is_numeric($materialid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CapacityModelMaterialService::findOne(['materialid' => $materialid]);
        $model->status=1;                
    	if($model){
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'更改失败']);
    
    }
}
