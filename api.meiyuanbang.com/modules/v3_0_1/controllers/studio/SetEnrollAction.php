<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;
use api\service\StudioEnrollService;
use api\service\OrderinfoService;
use api\service\OrdergoodsService;

/**
 * 报名方式写入
 *
 */
class SetEnrollAction extends ApiBaseAction {

    public function run() {
        //班型id
        $uid = $this->requestParam('uid'); //班型id
        $classtypeid = $this->requestParam('classtypeid'); //班型id
        $enrollid = $this->requestParam('enrollid'); //画室班型的报名方式id
        $name = $this->requestParam('name'); //画室班型的报名人姓名
        $mobile = $this->requestParam('mobile'); //画室班型的报名方式 填写的电话
        $QQ = $this->requestParam('QQ') ? $this->requestParam('QQ') : ''; //画室班型的报名方式 填写的qq号码
        $school = $this->requestParam('school') ? $this->requestParam('school') : ''; //画室班型的报名人学校
       //判断域名

        if ($enrollid) {
            $subject_info = StudioEnrollService::find()->where(['enrollid' => $enrollid])->asArray()->one();
            if ($subject_info) {
                $fee = $subject_info['discount_price']; #折扣价
                $mark = $enrollid;
                $ordertitle = $subject_info['enroll_title']; #标题
                $orderdesc = $subject_info['enroll_desc']; #资费说明
                $subject_info['fee'] = $fee;
                $subject_info['subjectid'] = $subject_info['enrollid'];
            } else {
                #购买画室不存在
                $data['message'] = '购买画室的班型不存在';
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
            }

            $orderid = OrderinfoService::addOrderInfo($uid, 3, $fee, $ordertitle, $orderdesc, $mark);
            if ($orderid) {
                //增加订单商品记录
                $data = $this->addOrderGoodsRec($orderid, $uid, $subject_info);
                $times =OrderinfoService::find()->select(['ctime'])->where(['orderid'=>$orderid])->asArray()->one();
                StudioService::SetEnroll($uid, $classtypeid, $enrollid, $name, $mobile, $QQ, $school,$times['ctime']);
                $data['orderid'] = $orderid;
                //返回结果
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
            } else {
                $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
            }
            //$this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
        } else {
            $ret = [];
            $this->controller->renderJson(1, $ret);
        }
    }

    public  function addOrderGoodsRec($orderid, $uid, $subject_info_arr) {
        #画室报名
        $status = OrdergoodsService::addOrderGood($orderid, $uid, 3, $subject_info_arr['enrollid'], $subject_info_arr['fee'], '');
        if ($status == 1) {
            $data['message'] = "已购买" . $subject_info_arr['enroll_title'];
        } else if ($status == 3) {
            $data['message'] = "写入失败！";
        }
        return $data;
    }

}
