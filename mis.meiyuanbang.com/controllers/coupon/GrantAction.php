<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponGrantService;

/**
 * 列表页
 */
class GrantAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_coupon';

    public function run() {
        $request = Yii::$app->request;
        $couponid=$request->get("couponid");
       	$data = CouponGrantService::getByPage($couponid);
		$data['couponid']= $couponid;
        return $this->controller->render('grant', $data);
    }

}
