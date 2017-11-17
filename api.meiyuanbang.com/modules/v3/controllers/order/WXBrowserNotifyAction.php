<?php

namespace api\modules\v3\controllers\order;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\WxBrowserPayCallBack;

/**
 * 微信公众号、浏览器支付通知接口
 * @author Administrator
 *
 */
class WXBrowserNotifyAction extends ApiBaseAction {

    public function run() {
        //创建微信支付处理类的实例
        $notify = new WxBrowserPayCallBack();
        //进入处理类，handle内部用回调来处理业务逻辑,false表示不输出签名
        $notify->Handle(false);
        //处理完业务逻辑后结束
        die('');
    }

}
