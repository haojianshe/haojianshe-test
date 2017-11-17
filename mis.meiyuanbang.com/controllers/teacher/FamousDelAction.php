<?php
namespace mis\controllers\teacher;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 取消殿堂老师
 */
class FamousDelAction extends MBaseAction
{	
	public $resource_id = 'operation_teacher';
	
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
    	$userid = $request->post('userid');
    	if(!$userid || !is_numeric($userid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = UserService::findOne(['uid' => $userid]);
    	if($model){
    		$model->ukind_verify = 0;
    		if($model->save()){
    			//清除用户缓存和殿堂老师列表缓存
    			UserService::removecache($userid);
    			UserService::remove_famousteacher_cache();
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'操作失败']);
    }
}
