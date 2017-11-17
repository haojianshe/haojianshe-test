<?php
namespace mis\controllers\teacher;

use Yii;
use mis\service\UserCorrectService;
use mis\components\MBaseAction;
use common\service\CommonFuncService;

/**
 * 变更红笔老师接收批改请求的状态
 */
class RedStatusAction extends MBaseAction
{
	public $resource_id = 'operation_teacher';
	
    public function run()
	{
    	$request = Yii::$app->request;
    	$isclose = false;
    	
    	$uid = $this->requestParam('userid');
    	if(!is_numeric($uid)){
    		die('非法输入');
    	}
    	//取得红笔老师数据
    	$redmodel = UserCorrectService::findOne(['uid' => $uid]);
    	$redmodel->IsNewRecord = false;
    	if($redmodel->status == 2 || $redmodel->status == 3){
    		$redmodel->status = 0;
    	}
    	else{
    		$redmodel->status = 2;
    	}    	
    	$redmodel->save();
    	//清除缓存    
    	UserCorrectService::removecache($uid);
		return $this->controller->outputMessage(['errno'=>0]);
    }
}
