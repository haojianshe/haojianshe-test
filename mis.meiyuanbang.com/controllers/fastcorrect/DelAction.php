<?php
namespace mis\controllers\fastcorrect;

use Yii;
use mis\components\MBaseAction;
use mis\service\FastCorrectService;

/**
 * mis快速批改删除action
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_activity';
	
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
    	$fastcorrectid = $request->post('fastcorrectid');
    	if(!$fastcorrectid || !is_numeric($fastcorrectid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = FastCorrectService::findOne(['fastcorrectid' => $fastcorrectid]);
    	if($model){
    		$model->is_del =1;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
