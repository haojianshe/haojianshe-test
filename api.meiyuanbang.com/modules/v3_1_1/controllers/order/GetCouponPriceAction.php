<?php

namespace api\modules\v3_1_1\controllers\order;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\UserCouponService;
use api\service\OrderinfoService;
use api\service\CourseService;
use api\service\OrdergoodsService;
/**
 * 订单使用优惠券计算价格
 */
class GetCouponPriceAction extends ApiBaseAction {

    public function run() {

        $orderid = $this->requestParam('orderid',true);
         //回滚未使用的优惠券订单信息 避免重复使用优惠券
        OrderinfoService::RollBackCoupon($orderid);

        //优惠券id
        $usercouponid = $this->requestParam('usercouponid',true);
        $uid = $this->_uid;
        $couponinfo=UserCouponService::getNoUserCouponInfo($uid,$usercouponid);
        if(!$couponinfo){
            die("优惠卷不存在！");
        }
        $ret=OrderinfoService::getCouponPrice($orderid,$couponinfo);

        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
    
    

}
