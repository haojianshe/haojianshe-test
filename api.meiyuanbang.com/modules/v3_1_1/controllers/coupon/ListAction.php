<?php

namespace api\modules\v3_1_1\controllers\coupon;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserCouponService;

/**
 * 得到用户优惠卷列表
 */
class ListAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        //优惠券id
        $lastid = $this->requestParam('lastid');
        //优惠券类型
        $status = $this->requestParam('status');

        $uid = $this->_uid;
        UserCouponService::removeRed($uid);
        $data = UserCouponService::getList($uid,$status,$lastid,$rn);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
