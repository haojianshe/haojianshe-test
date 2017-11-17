<?php
namespace api\modules\v3\controllers\order;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\OrderinfoService;
use common\service\AliPayService;
use common\service\WxPayService;

/**
 * 支付签名接口
 * 支付宝直接签名即可
 * 微信支付需要先根据订单信息在微信端生成预支付订单，根据返回接口组织调起微信支付需要的各项参数和签名
 */
class PaySignAction extends ApiBaseAction{
	
   public  function run(){
   		//检查orderid是否正常
   		$model = $this->orderCheck();
        //判断是微信支付还是支付宝支付
   		$paytype=$this->requestParam('paytype',true);
   		if($paytype=='1'){
   			//微信支付先调用统一下单接口
   			$preOrderModel=$this->wxUnifiedOrder($model);
   			if($preOrderModel['return_code']!='SUCCESS' || $preOrderModel['result_code']!='SUCCESS'){
   				//统一下单接口调用不成功
   				$this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST,$preOrderModel);
   			}
   			//得到调起微信支付的各项参数
   			$tmp = WxPayService::getWXAppPayParams($preOrderModel);
   			//客户端不能处理package字符，所以去掉
   			$tmp['package_wx'] = $tmp['package'];
   			unset($tmp['package']);
   			$ret['wxsign']=$tmp;
   		}
   		else{
   			//支付宝支付paytype=2
   			$ret['alisign']=$this->getAlipaySign($model);
   		}        
   		$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    
    /**
     *  支付宝支付签名
     */
    private function getAlipaySign($model){
    	//暂时先写死
    	$appid = '2016040801277575';    	
    	$params['app_id'] = $appid;
    	$params['charset'] = 'utf-8';
    	$params['method'] = 'alipay.trade.app.pay';
    	$params['sign_type'] ='RSA2';
    	$params['timestamp'] = (string)date("Y-m-d H:i:s",time());
    	$params['version'] = '1.0';
    	$params['notify_url'] = Yii::$app->params['alipay_notifyurl'];
    	//业务数据    	
    	$biz='{"timeout_express":"30m","product_code":"QUICK_MSECURITY_PAY","total_amount":"';
    	$biz .= $model['fee'] . '","subject":"' . $model['ordertitle'] .'","body":"'. $model['orderdesc'] .'","out_trade_no":"'. $model['orderid'] .'"}';
    	$params['biz_content']=$biz;
    	$ret = AliPayService::sign($params, $params['sign_type']);
    	$params['sign']=$ret;
    	//所有value做urlencode
    	foreach ($params as $k=>$v){
    		$params[$k]=urlencode($v);
    	}    	
    	return $params;
    }
    
    /**
     * 微信调用统一下单接口
     * unified方法中已经包含了对返回值的签名校验
     */
    private function wxUnifiedOrder($model){
    	$orderid = $this->requestParam('orderid',true);
    	//微信支付金额为分，需要把元转化成分
    	$model['fee']=$model['fee']*100;
    	//标题中有特殊符号签名会不成功
    	$model['ordertitle'] = '美院帮线上课程';
    	//调用统一下单接口
    	$ret = WxPayService::unified($model);
    	return $ret;
    }
    
    /**
     * 检查订单合法性
     * @return \api\service\[type]
     */
    private function orderCheck(){
    	$orderid = $this->requestParam('orderid',true);
    	$model = OrderinfoService::getOrderDetail($orderid);
    	
    	//检查id是否有效
    	if(!$model){
    		die('error orderid');
    	}
    	//检查订单是为已支付订单
    	if($model['status']=='1'){
    		die('订单已支付');
    	}
    	//检查订单是否为本人
    	if($model['uid']!=$this->_uid){
    		die('订单非本人');
    	}
    	return $model;
    }
}