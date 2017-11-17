<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;
use mis\service\UserCorrectService;

/**
 * 取消红笔老师身份
 */
class RedDelAction extends MBaseAction
{	
	public $resource_id = 'operation_teacher';
	
    /**
     * 只支持post
     */
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	//检查参数是否非法
    	$uid = $request->post('userid');    	
    	if(!$uid || !is_numeric($uid)){
    		die('参数不正确');
    	}
    	//取消红笔老师列表,需要修改detail表和红笔老师表两个地方的数据
    	$usermodel = UserService::findOne(['uid' => $uid]);
    	$usermodel->featureflag = null;
    	$usermodel->save();
    	$redmodel = UserCorrectService::findOne(['uid' => $uid]);
    	$redmodel->IsNewRecord = false;
        $redmodel->status = 1;
        $redmodel->correct_fee = 0;
    	$redmodel->correct_fee_ios = 0;
    	$redmodel->save();
    	//清除对应的红笔老师
    	UserCorrectService::removecache($uid);
    	//清除对应的用户缓存
    	UserService::removecache($uid);
    	return $this->controller->outputMessage(['errno'=>0,'msg'=>'取消红笔老师成功']);
    }
}