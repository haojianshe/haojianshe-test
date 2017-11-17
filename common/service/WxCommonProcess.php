<?php

namespace common\service;

use Yii;
use yii\base\Object;
use api\service\OrderactionService;
use api\service\OrderinfoService;

/**
 * 微信支持多种支付方式，app和公众号支付是不同的商户号，只能分别封装，但是处理过程一样
 * 此方法提取公共订单处理代码，微信支付，公众号和app支付后都可以调用此接口
 */
class WxCommonProcess extends Object {

    /**
     * 重写WxPayNotify类的回调的处理函数
     * 在此函数中处理微信支付的业务逻辑
     */
    static function CommonProcess($data, &$msg) {
        //目前只处理通信和业务都成功的处理结果
        if ($data['return_code'] != 'SUCCESS' || $data['result_code'] != 'SUCCESS') {
            $msg = "支付不成功";
            file_put_contents($logfile, $data['out_trade_no'] . '支付不成功', FILE_APPEND);
            return false;
        }
        //获取订单号
        if (!array_key_exists("out_trade_no", $data)) {
            $msg = "订单号不正确";
            return false;
        }
        $out_trade_no = $data['out_trade_no'];
        //微信订单号
        $transaction_id = $data['transaction_id'];
        //支付时间
        $time_end = $data['time_end'];
        //获取订单表中的信息
        $orderModel = OrderinfoService::findOne(['orderid' => $out_trade_no]);
        if (!$orderModel) {
            $msg = "订单号不正确";
            return false;
        }
        //判断订单是否已经支付过，微信可能会多次通知
        if ($orderModel->status == 1) {
            return true;
        }
        //订单操作表插入支付记录
        $model = new OrderactionService();
        $model->orderid = $out_trade_no;
        $model->uid = $orderModel->uid;
        //1 支付
        $model->actiontype = 1;
        //0失败  1成功
        $model->action_status = 1;
        $model->actiontime = strtotime($time_end);
        $model->ctime = time();
        //记录微信传过来的所有参数
        $model->action_note = json_encode($data);
        //对于微信或支付宝支付，用mark字段用来标记支付来源：app jsapi h5等
        $model->mark = $data['trade_type'];
        if ($model->save()) {
            //改变订单状态
            $orderModel->paytime = time();
            //支付方式 1微信 2支付宝
            $orderModel->paytype = 1;
            //状态：1为已支付
            $orderModel->status = 1;
            if ($orderModel->save()) {
                OrderinfoService::addBounty($orderModel);
                //两个表信息都保存成功后返回给支付宝成功消息
                return true;
            }
        }
        $msg = "保存数据库失败";
        return false;
    }

}
