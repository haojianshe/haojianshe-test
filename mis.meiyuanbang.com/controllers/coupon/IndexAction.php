<?php

namespace mis\controllers\coupon;

use Yii;
use mis\components\MBaseAction;
use mis\service\CouponService;

/**
 * 列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_coupon';

    public function run() {
        $request = Yii::$app->request;
        $search_arr['coupon_name'] = trim($request->get("coupon_name")); #标题
        $data = CouponService::getByPage($search_arr['coupon_name']);
        $data['search_arr']=$search_arr;
        return $this->controller->render('index', $data);
    }

}
