<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponGrantService;

/**
 * 列表页
 */
class GrantUserAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_coupon';

    public function run() {
        $request = Yii::$app->request;
        $coupongrantid=$request->get("coupongrantid");
        $data['models'] = CouponGrantService::getUserList($coupongrantid);
        return $this->controller->render('grantuser', $data);
    }

}
