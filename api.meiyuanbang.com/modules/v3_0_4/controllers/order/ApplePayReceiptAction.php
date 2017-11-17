<?php

namespace api\modules\v3_0_4\controllers\order;

use Yii;
use api\components\ApiBaseAction;
use api\service\OrderactionService;
use api\service\OrderinfoService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\AppleInAppPayService;

/**
 * ios端苹果支付成功后服务器进行二次校验
 *     苹果校验返回值确认
 *     21000 App Store不能读取你提供的JSON对象
     * 21002 receipt-data域的数据有问题
     * 21003 receipt无法通过验证
     * 21004 提供的shared secret不匹配你账号中的shared secret
     * 21005 receipt服务器当前不可用
     * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
     * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
     * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
 */
class ApplePayReceiptAction extends ApiBaseAction {

    public function run() {        
    	//订单编号
    	$orderid = $this->requestParam('orderid',true);
    	//时间戳
    	$tp =  $this->requestParam('tp',true);
    	//美院帮签名
    	$mybsign = $this->requestParam('mybsign',true);
    	//苹果二次校验收据
    	$receipt = $this->requestParam('receipt',true);
    	
    	//返回数据，如果errmsg为''，则表示验证成功
    	$ret['errmsg'] = '';
    	$ret['orderid'] = $orderid;
    	//(1)先进行美院帮签名校验
    	if(AppleInAppPayService::checkMybSign($mybsign, $receipt, $orderid, $tp)==false){
    		die('签名错误');
    	}
    	//订单支付状态检查
    	$orderModel = OrderinfoService::findOne(['orderid'=>$orderid]);
    	if(!$orderModel){
    		die('error orderid');
    	}
    	//判断订单是否已经支付过
    	if($orderModel->status==1){
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	}
    	//(2)保存验证收据到myb_orderaction表，以防意外原因不能验证成功
    	//订单操作表插入支付记录
    	$model=new OrderactionService();
    	$model->orderid = $orderid;
    	$model->uid = $this->_uid;
    	//2 表示苹果支付
    	$model->actiontype=2;
    	//支付前先设置为支付失败
       	$model->action_status = 0;
    	$model->actiontime = time();
    	//记录验证失败次数
    	$model->mark = '0';
    	$model->ctime=time();
    	//暂存支付收据
    	$model->action_note = $receipt;
    	if(!$model->save()){
    		$this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
    	}
    	//(3)通过苹果正式地址进行收据验证
    	$response = AppleInAppPayService::receipt($receipt, false);
    	$response = json_decode($response);
    	//(4)如果正式地址返回21007错误，则通过sandbox地址进行验证
    	if($response->status==21007){
    		$response = AppleInAppPayService::receipt($receipt, true);
    		$response = json_decode($response);
    	}
    	//(5)收据验证成功后，更改订单状态，记录交易信息
    	if($response->status<>0){
    		$ret['errmsg'] = '苹果内购收据验证错误';
    		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    	}
    	//改变订单状态
    	$orderModel->paytime =time();
    	//支付方式 3苹果内购
    	$orderModel->paytype = 3;
    	//状态：1为已支付
    	$orderModel->status =1;
    	if(!$orderModel->save()){
    		$this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
    	}
    	//(6)订单操作表执行结果变为1,支付成功
    	$model->action_status=1;
    	//记录交易信息
    	$model->action_note = json_encode($response->receipt);
    	$model->save();
        //老师增加佣金
        OrderinfoService::addBounty($orderModel);
    	//返回支付成功
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
