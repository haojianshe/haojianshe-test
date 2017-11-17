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
 * 提交优惠券更改订单信息
 */
class SubmitCouponAction extends ApiBaseAction {

    public function run() {

        $orderid = $this->requestParam('orderid',true);
         //回滚未使用的优惠券订单信息 避免重复使用优惠券
        OrderinfoService::RollBackCoupon($orderid);

        //用户优惠卷id
        $usercouponid = $this->requestParam('usercouponid',true);
        $uid = $this->_uid;
        $couponinfo=UserCouponService::getNoUserCouponInfo($uid,$usercouponid);
        if(!$couponinfo){
            die("优惠卷不存在！");
        }
        //计算优惠卷价格
        $ret_info=OrderinfoService::getCouponPrice($orderid,$couponinfo);
        if($ret_info['orderinfo']['coupon_price']>0){
            if($ret_info['orderinfo']['usercouponid']){
                //订单已使用优惠卷
                $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT); 
            }
            if($ret_info['orderinfo']['groupbuyid']){
                //团购订单不允许使用优惠券
                $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT); 
            }
            //更改订单价格
            $ret_status=OrderinfoService::UpdateFeeByCoupon($uid,$orderid,$usercouponid,$ret_info['orderinfo']['coupon_price']);
            if(!$ret_status){
               $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);  
            }
            
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret_info);
    }
    
    

}
