<?php
namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponService;
/**
 * 删除
 */
class DelAction extends MBaseAction
{	
	public $resource_id = 'operation_coupon';
	
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
    	$couponid = $request->post('couponid');
        $status = $request->post('status');
        if(!($status==1 || $status==2)){
            die('参数不正确');
        }
    	if(!$couponid || !is_numeric($couponid)){
    		die('参数不正确');
    	}
    	//根据id取出数据
    	$model = CouponService::findOne(['couponid' => $couponid]);
    	if($model){
    		$model->status =$status;
    		$ret = $model->save();
    		if($ret){
    			return $this->controller->outputMessage(['errno'=>0]);
    		}
    	}
    	return $this->controller->outputMessage(['errno'=>1,'msg'=>'删除失败']);
    }
}
