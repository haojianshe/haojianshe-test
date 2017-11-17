<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserCouponService;

/**
 * 列表页
 */
class UserAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_coupon';

    public function run() {
        $request = Yii::$app->request;
        $couponid = $request->get('couponid');
        $data = UserCouponService::getByPage($couponid);
        return $this->controller->render('user', $data);
    }

}
