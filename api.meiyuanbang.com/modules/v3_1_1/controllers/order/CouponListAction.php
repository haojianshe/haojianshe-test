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
 * 得到用户优惠卷列表
 */
class CouponListAction extends ApiBaseAction {

    public function run() {
        //优惠券类型(已使用) 1=>未使用，2=>已使用，3=>已删除 4=》已过期
        $orderid = $this->requestParam('orderid',true);
        //回滚未使用的优惠券订单信息 避免重复使用优惠券
        OrderinfoService::RollBackCoupon($orderid);
        $uid = $this->_uid;
        //筛选未使用的优惠卷
        $data = UserCouponService::getNotUsedList($uid);
        $ret=[];
        //筛选能用的优惠卷
        foreach ($data as $key => $value) {  
            $ret_info=OrderinfoService::getCouponPrice($orderid,$value);
            if((float)$ret_info['orderinfo']['coupon_price']>0){
                $ret[]=$value;
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }

   
}
