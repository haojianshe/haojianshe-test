<?php
namespace mis\controllers\push;

use Yii;
use mis\components\MBaseAction;
use mis\service\MisXingePushService;
use common\service\XingeAppService;

/**
 * mis推送删除action
 */
class CancelPushAction extends MBaseAction
{	
    /**
     * 只支持post删除
     */
    public $resource_id = 'operation_push';
    public function run()
    {
    	$request = Yii::$app->request;
    	if(!$request->isPost){
    		die('非法请求!');
    	}
    	$params = $request->post('params');
        $id=$request->post('id');
        //params数据类型  android,ios
        $paramsarr=explode(',', $params);
        if($paramsarr[0]!= -1){
            XingeAppService::cancel_android_push($paramsarr[0]);
        }
        if($paramsarr[1]!= -1){
            XingeAppService::cancel_ios_push($paramsarr[1]);
        }
    	//根据id取出数据
    	$model = MisXingePushService::findOne(['id' => $id]);        
    	if($model){
    		$model->state =2;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
