<?php

namespace api\modules\v3\controllers\order;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\OrderactionService;
use api\service\OrderinfoService;
use common\service\AliPayService;

/**
 * 支付宝手机支付通知页面
 * @author ihziluoh
 *
 */
class NotifyUrlAction extends ApiBaseAction {

    public function run() {
        //log
        $arr = $_REQUEST;
        #$logfile = __DIR__ . '/../../../../../api.meiyuanbang.com/runtime/logs/wxpay.txt';
        #file_put_contents($logfile, json_encode($arr), FILE_APPEND);
        #exit;
        //获取支付宝参数
        #$arr = $_POST;
        $rsaType = $arr['sign_type'];
        //进行签名验证
        $result = AliPayService::rsaCheck($arr, $rsaType);
        //签名失败
        if (!$result) {
            die('fail');
        }
        //通过校验
        //用户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //支付宝订单号
        $trade_no = $_POST['trade_no'];
        //交易状态
        $trade_status = $_POST['trade_status'];
        $notify_time = $_POST['notify_time'];

        if ($trade_status != 'TRADE_SUCCESS') {
            //目前通知接口只支持支付成功，退款等状态目前不处理
            die('fail');
        }
        //获取订单表中的信息
        $orderModel = OrderinfoService::findOne(['orderid' => $out_trade_no]);
        if (!$orderModel) {
            die('fail');
        }
        //判断订单是否已经支付过
        if ($orderModel->status == 1) {
            die('success');
        }
        //订单操作表插入支付记录
        $model = new OrderactionService();
        $model->orderid = $out_trade_no;
        $model->uid = $orderModel->uid;
        //1 支付
        $model->actiontype = 1;
        //0失败  1成功
        $model->action_status = 1;
        $model->actiontime = strtotime($notify_time);
        $model->ctime = time();
        //记录支付宝传过来的所有参数
        $model->action_note = json_encode($arr);
        if ($model->save()) {
            //改变订单状态
            $orderModel->paytime = time();
            //支付方式 1微信 2支付宝  支付宝手机支付
            $orderModel->paytype = 5;
            //状态：1为已支付
            $orderModel->status = 1;
            if ($orderModel->save()) {
                OrderinfoService::addBounty($orderModel);
                //两个表信息都保存成功后返回给支付宝成功消息
                die('success');
            }
        }
        die('fail');
    }

}
