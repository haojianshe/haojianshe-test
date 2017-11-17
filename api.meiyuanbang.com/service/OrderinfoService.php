<?php

namespace api\service;

use Yii;
use common\models\myb\Orderinfo;
use api\service\OrdergoodsService;
use api\service\CourseSectionVideoService;
use api\service\TeacherBountyService;
use api\service\GroupBuyService;
use api\service\CorrectService;
use api\service\CorrectRewardService;

/**
 * @author ihziluoh
 * 
 * 订单信息表
 */
class OrderinfoService extends Orderinfo {

    /**
     * 根据订单id 获取订单信息
     * @return [type] [description]
     */
    public static function getOrderDetail($orderid) {
        $orderinfo = self::find()->where(['orderid' => $orderid])->asArray()->one();
        if (empty($orderinfo)) {
            $orderinfo = [];
        }
        return $orderinfo;
    }

    /**
     * 增加订单记录
     */
    public static function addOrderInfo($uid, $subjecttype, $fee, $ordertitle, $orderdesc, $mark, $teacheruid = NULL, $recommend_from = NULL, $devicetype = NULL, $groupbuyid = NULL, $coupon_price = NULL) {
        $model = new Orderinfo();
        $model->uid = $uid; // '购买用户' ,
        $model->subjecttype = $subjecttype; // '订单类型 :1直播  2点播' ,
        $model->mark = $mark; // 购买商品id        
        $model->fee = $fee; // '订单费用' ,
        $model->status = 0; // '订单状态:0未支付  1已支付' ,
        $model->ordertitle = $ordertitle; // '订单标题' ,
        $model->orderdesc = $orderdesc; // '订单简介' ,
        $model->coupon_price = $coupon_price; // '优惠价格' ,
        $model->order_from = $devicetype; // '订单来源' ,
        $model->groupbuyid = $groupbuyid; // '团购id' ,
        $model->ctime = time(); // '订单生成时间' ,
        if ($teacheruid) {
            $model->teacheruid = $teacheruid;
        }
        if ($recommend_from) {
            $model->recommend_from = $recommend_from;
        }
        $ret = $model->save();
        if ($ret) {
            return $model->attributes['orderid'];
        } else {
            return false;
        }
    }

    /**
     * 得到用户购买商品记录
     * @param  [type]  $uid    [description]
     * @param  [type]  $lastid [description]
     * @param  integer $rn     [description]
     * @return [type]          [description]
     */
    public static function getBuyGoodsDb($uid, $lastid = NULL, $rn = 10) {
        $query = self::find()->alias("a")->select("a.*")->where(['a.uid' => $uid])->andWhere(['a.status' => 1]);
        if (intval($lastid) > 0) {
            $query->andWhere(['<', 'orderid', $lastid]);
        }
        return $query->orderBy("orderid desc")->limit($rn)->asArray()->all();
    }

    /**
     * 得到商品购买状态 订单类型 :1直播  2点播 1/2 未购买/已购买
     * @param  [type] $uid         [description]
     * @param  [type] $subjecttype [description]
     * @param  [type] $subjectid   [description]
     * @return [type]              [description]
     */
    public static function getByGoodStatus($uid, $subjecttype, $subjectid) {
        $find = self::find()->where(['uid' => $uid])->andWhere(['subjecttype' => $subjecttype])->andWhere(['mark' => $subjectid])->andWhere(['status' => 1])->one();
        if ($find) {
            //已购买
            return 2;
        } else {
            //未购买
            return 1;
        }
    }

    /**
     * 增加老师佣金 || 修改老师打赏表
     * @param [array] $orderid [description]
     */
    public static function addBounty($orderinfo) {
        //$orderinfo=self::find()->where(['orderid'=>$orderid])->asArray()->one();
        //老师获得的佣金
        $bounty_fee = 0;
        switch (intval($orderinfo['subjecttype'])) {
            case 2:
                $info = CourseService::findOne(['courseid' => $orderinfo['mark']]);
                //判断订单是否ios内购
                if ($info['buy_type'] == 2) {
                    if ($orderinfo['paytype'] == 3) {
                        $bounty_fee = ($info['course_bounty_fee_ios']);
                    } else {
                        $bounty_fee = ($info['course_bounty_fee']);
                    }
                } else if ($info['buy_type'] == 1) {
                    $bounty_fee = 0;
                    $goodinfo = OrdergoodsService::find()->where(['orderid' => $orderinfo['orderid']])->asArray()->all();
                    foreach ($goodinfo as $key => $value) {
                        $videoinfo = CourseSectionVideoService::find()->where(['coursevideoid' => $value['subjectid']])->asArray()->one();
                        if ($orderinfo['paytype'] == 3) {
                            //判断订单是否ios内购
                            $bounty_fee+=$videoinfo['bounty_fee_ios'];
                        } else {
                            $bounty_fee+=$videoinfo['bounty_fee'];
                        }
                    }
                }

                TeacherBountyService::add($orderinfo['teacheruid'], $orderinfo['recommend_from'], $orderinfo['orderid'], $orderinfo['uid'], $orderinfo['subjecttype'], $orderinfo['mark'], $bounty_fee);
                break;
            default:
                break;
        }
    }

    /**
     * 得到商品购买状态 订单类型 :1直播  2点播 1/2 未购买/已购买
     * @param  [type] $uid         [description]
     * @param  [type] $subjecttype [description]
     */
    public static function getUserOrderSuccess($uid, $subjecttype = 2) {
       return self::find()->select("sum(fee) as money,count(*) as info")->where(['uid' => $uid])->andWhere(['subjecttype' => $subjecttype])->andWhere(['status' => 1])->asArray()->one();
    }

    public static function getCouponPrice($orderid, $couponinfo) {
        //优惠券使用范围
        $min_price = $couponinfo['min_price'];
        $max_price = $couponinfo['max_price'];
        //1=>课程,2=>直播，3=>全部
        $coupon_type = $couponinfo['coupon_type'];
        //返回状态 false 不可用 true 可以使用
        $orderinfo = OrderinfoService::getOrderDetail($orderid);
        if ($orderinfo['status'] == 1) {
            die('订单已支付！');
        }
        switch (intval($orderinfo['subjecttype'])) {
            //订单类型 :1直播  2点播 3画室班型报名方式
            case 1:
                //直播优惠卷判断
                // coupon_type 1=>课程,2=>直播，3=>全部

                if ($min_price <= $orderinfo['fee'] && $max_price >= $orderinfo['fee'] && ($coupon_type == 2 || $coupon_type == 3 )) {
                    $orderinfo['coupon_price'] = $orderinfo['fee'];
                    $orderinfo['fee'] = 0;
                }
                break;
            case 2:

                //课程优惠卷判断
                if ($coupon_type == 1 || $coupon_type == 3) {
                    $courseinfo = CourseService::find()->where(['courseid' => $orderinfo['mark']])->asArray()->one();
                    if ($courseinfo['buy_type'] == 2) {

                        //2=>整课购买
                        if ($min_price <= $orderinfo['fee'] && $max_price >= $orderinfo['fee']) {
                            $orderinfo['coupon_price'] = $orderinfo['fee'];
                            $orderinfo['fee'] = 0;
                        }
                    } elseif ($courseinfo['buy_type'] == 1) {
                        //1=>分课时购买
                        $ordergoods = OrdergoodsService::getOrderGoodsByPrice($orderid, $orderinfo['uid'], $orderinfo['subjecttype']);
                        //循环每一节信息
                        foreach ($ordergoods as $key1 => $value1) {
                            if ($min_price <= $value1['fee'] && $max_price >= $value1['fee']) {
                                $orderinfo['fee'] = $orderinfo['fee'] - $value1['fee'];
                                $orderinfo['coupon_price'] = $value1['fee'];
                                break;
                            }
                        }
                    }
                }
                break;
            default:
                break;
        }


        $ret['orderinfo'] = $orderinfo;
        return $ret;
    }

    /**
     * 通过优惠价格更改订单
     * @param [type] $uid          [description]
     * @param [type] $orderid      [description]
     * @param [type] $coupon_price [description]
     */
    public static function UpdateFeeByCoupon($uid, $orderid, $usercouponid, $coupon_price) {
        //订单状态:0未支付  1已支付
        $orderinfo = OrderinfoService::findOne(['orderid' => $orderid, 'uid' => $uid, 'status' => 0]);
        $orderinfo->fee = $orderinfo->fee - $coupon_price;
        $orderinfo->coupon_price = $coupon_price;
        $orderinfo->usercouponid = $usercouponid;
        //课程卷购买逻辑
        if ($orderinfo->fee == 0) {
            //支付类型 1微信  2支付宝 3apple内购 4 课程券购买（全部课程卷购买）
            $orderinfo->paytype = 4;
            //订单状态:0未支付  1已支付
            $orderinfo->status = 1;
        }
        $ret = $orderinfo->save();
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 保存时操作
     * @param  boolean $runValidation  [description]
     * @param  [type]  $attributeNames [description]
     * @return [type]                  [description]
     */
    public function save($runValidation = true, $attributeNames = NULL) {
        $isnew = $this->isNewRecord;
        $ret = parent::save($runValidation, $attributeNames);
        //更新课程卷状态改为已使用
        if ($this->usercouponid && $this->status == 1) {
            UserCouponService::updateCouponStatus($this->usercouponid, $this->uid);
        }
        //团购
        if($this->groupbuyid && $this->status==1 ){
             GroupBuyService::updateBuyCount($this->groupbuyid);
        }
        //处理老师打赏逻辑，修改打赏表
        if ($this->subjecttype == 4 && $this->status == 1) {
            CorrectRewardService::updateTeacherReward($this->mark);
        }

        //付费批改
        if($this->subjecttype==5 && $this->status==1 ){
            //更改批改支付状态 及支付金额
             CorrectService::updateBuyStatus($this->mark,$this->fee);
        }

        return $ret;
    }

    /**
     * 回滚优惠信息
     * @param  [type] $orderid [description]
     * @return [type]          [description]
     */
    public static function RollBackCoupon($orderid) {
        //未支付订单
        $ret = true;
        $orderinfo = self::findOne(['orderid' => $orderid, 'status' => 0]);
        if ($orderinfo) {
            //订单存在并且 未支付的存在优惠信息则回滚到 用优惠券前的状态
            if ($orderinfo->usercouponid) {
                $orderinfo->usercouponid = '';
                $orderinfo->fee = $orderinfo->fee + $orderinfo->coupon_price;
                $orderinfo->coupon_price = '';
                $ret = $orderinfo->save();
            }
        }
        return $ret;
    }

    /**
      得到用户是否参加了团购状态  1已参加 0 未参加
     * */
    public static function getGroupByStatus($groupbuyid, $uid) {
        $orderinfo = self::find()->where(['groupbuyid' => $groupbuyid])->andWhere(['uid' => $uid])->andWhere(['status' => 1])->one();
        if ($orderinfo) {
            return 1;
        } else {
            return 0;
        }
    }

}
