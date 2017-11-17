<?php

namespace api\modules\v3\controllers\order;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
//use api\service\OrderactionService;
use api\service\OrdergoodsService;
use api\service\OrderinfoService;
use api\service\LiveService;
use api\service\CourseSectionVideoService;
use api\service\CourseService;
//use api\service\CourseSectionService;
use api\service\StudioEnrollService;
use api\service\StudioService;
use api\service\GroupBuyService;
use api\service\CorrectService;
use api\service\UserCorrectService;
use api\service\CorrectRewardService;
use common\service\dict\CorrectGiftService;

/**
 * 创建订单
 * @author ihziluoh
 *
 */
class CreateAction extends ApiBaseAction {

    public function run() {
        //如果获取token失败，创建订单不能继续
        $uid = $this->_uid;
        if ($uid == -1) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }
        $subjecttype = $this->requestParam('subjecttype', true);
        $subjectid = $this->requestParam('subjectid');
        $isiospay = $this->requestParam('isiospay');
        $enrollid = $this->requestParam('enrollid'); //画室班型的报名方式id
        $teacheruid = $this->requestParam('teacheruid'); //推荐课程老师uid
        $recommend_from = $this->requestParam('recommend_from'); //推荐来源1=>批改,2=>私信
        $devicetype = $this->requestParam('devicetype'); //推荐来源1=>批改,2=>私信
        $groupbuyid = $this->requestParam('groupbuyid'); //团购id目前只有课程有
        $teacheruid_gift = $this->requestParam('teacheruid_gift'); //打赏老师的id
        //创建班型订单
        if ($enrollid) {
            $classtypeid = $this->requestParam('classtypeid', true); //班型id
            $name = $this->requestParam('name', true); //画室班型的报名人姓名
            $mobile = $this->requestParam('mobile', true); //画室班型的报名方式 填写的电话
            $QQ = $this->requestParam('QQ') ? $this->requestParam('QQ') : ''; //画室班型的报名方式 填写的qq号码
            $school = $this->requestParam('school') ? $this->requestParam('school') : ''; //画室班型的报名人学校 
        }
        //初始化生成订单参数
        $fee = 0;
        $coupon_price = 0;
        $ordertitle = '';
        $orderdesc = '';
        $mark = 0;
        //存储购买实体信息
        $subject_info = [];
        //:1直播  2点播 3报名画室班型
        switch (intval($subjecttype)) {
            case 1:
                //直播信息
                $subject_info = LiveService::find()->where(['liveid' => $subjectid])->asArray()->one();
                if ($subject_info) {
                    $status = LiveService::getLiveStatus($subject_info['start_time'], $subject_info['end_time']);
                    //购买价格：1=>正在预告,2=>直播中,3=>直播结束
                    switch (intval($status)) {
                        case 1:
                        case 2:
                            //订单总价
                            if ($isiospay == 1) {
                                //ios 价格单独计算
                                $fee = $subject_info['live_ios_price'];
                            } else {
                                $fee = $subject_info['live_price'];
                            }
                            break;
                        case 3:
                            //订单总价
                            if ($isiospay == 1) {
                                //ios 价格单独计算
                                $fee = $subject_info['recording_ios_price'];
                            } else {
                                $fee = $subject_info['recording_price'];
                            }

                            break;
                    }
                    $mark = $subjectid;
                    $ordertitle = $subject_info['live_title'];
                    $orderdesc = $subject_info['live_title'];
                    $subject_info['fee'] = $fee;
                } else {
                    //购买直播不存在
                    $data['message'] = 'Not Found This Live Video';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
                break;
            case 2:
                $courseid = $this->requestParam('courseid');
                if ($courseid) {
                    $fcourse = CourseService::find()->where(['courseid' => $courseid])->asArray()->one();
                    if ($fcourse['buy_type'] == 2) {
                        //订单总价
                        if ($isiospay == 1) {
                            //ios 价格单独计算
                            $fee = $fcourse['course_price_ios'];
                        } else {
                            $fee = $fcourse['course_sale_price'];
                        }
                        $ordertitle = $fcourse['title'];
                        $orderdesc = $fcourse['teacher_desc'];
                        $mark = $courseid;
                        $subject_info = [];
                        //整课团购 团购价格处理
                        if ($groupbuyid) {
                            $groupbuyinfo = GroupBuyService::getInfoByGroupBuyId($groupbuyid);
                            if ($groupbuyinfo) {
                                //订单总价
                                if ($isiospay == 1) {
                                    //ios 价格单独计算
                                    $fee = $groupbuyinfo->course_group_fee_ios;
                                    $coupon_price = $fcourse['course_price_ios'] - $fee;
                                } else {
                                    $fee = $groupbuyinfo->course_group_fee;
                                    $coupon_price = $fcourse['course_sale_price'] - $fee;
                                }
                                if ($coupon_price < 0) {
                                    $coupon_price = 0;
                                }
                            }
                        }
                        break;
                    }
                }
                //课程视频信息
                $coursevideoids_arr = explode(",", $subjectid);
                //循环取每个视频信息计算订单总价及订单描述
                foreach ($coursevideoids_arr as $key => $value) {
                    $subject_info_item = CourseSectionVideoService::getOrderCourseVideoInfo($value);

                    //存储购买实体信息
                    $subject_info[] = $subject_info_item;
                    if ($subject_info_item) {
                        //订单总价
                        if ($isiospay == 1) {
                            //ios 价格单独计算
                            $fee+=($subject_info_item['ios_price']);
                        } else {
                            $fee+=($subject_info_item['sale_price']);
                        }
                        //订单描述 （所有节标题）
                        $orderdesc.=$subject_info_item['section_num'] . '_' . $subject_info_item['section_video_num'] . $subject_info_item['video_title'] . ',';
                        $mark = $subject_info_item['courseid'];
                    } else {
                        //购买课程不存在
                        $data['message'] = 'Not Found One Course Video';
                        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                    }
                }
                $ordertitle = $subject_info_item['title'];
                $orderdesc = substr($orderdesc, 0, strlen($orderdesc) - 1);
                break;
            case 3: //班型报名方式信息
                $subject_info = StudioEnrollService::find()->where(['enrollid' => $subjectid])->asArray()->one();
                if ($subject_info) {
                    $fee = $subject_info['discount_price']; //折扣价
                    $mark = $subjectid;
                    $ordertitle = $subject_info['enroll_title']; //标题
                    $orderdesc = $subject_info['enroll_desc']; //资费说明
                    $subject_info['fee'] = $fee;
                } else {
                    //购买画室不存在
                    $data['message'] = '购买画室的班型不存在';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
                break;
            case 4; //老师打赏 下订单
                $ret = CorrectGiftService::getGiftOneList($subjectid);
               
                if ($ret) {
                    $mark = CorrectRewardService::setPewardInfo($subjectid, $ret[0]['gift_price'],$ret[0]['gift_name'], $uid, $teacheruid_gift);
                 
                    if ($mark) {
                        $fee = $ret[0]['gift_price'];
                        $ordertitle = $ret[0]['gift_name'] . '（礼物）';
                        $orderdesc =$ret[0]['gift_name'] . '（礼物）';
                    }
                } else {
                    //购买画室不存在
                    $data['message'] = '送礼物失败';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
                $subject_info = [
                    'fee' => $ret[0]['gift_price'],
                    'subjectid' => $mark,
                    'gift_name' => $ret[0]['gift_name']
                ];
                break;
            case 5: #付费批改
                $subject_info = CorrectService::getFullCorrectInfo($subjectid,$uid);
                $teacher_info=UserCorrectService::getUserCorrectDetail($subject_info['teacheruid']);
                
                if ($subject_info && $teacher_info) {
                    $mark = $subjectid;
                    //订单价格
                    if($isiospay==1){
                        //ios 价格单独计算
                       $fee=$teacher_info['correct_fee_ios'];
                    }else{
                       $fee=$teacher_info['correct_fee'];
                    }
                    $subject_info['fee'] = $fee;
                    $ordertitle = "付费批改——".$subject_info['teacher_info']['sname']; #标题
                    $orderdesc = $subject_info['content']; #订单描述
                } else {
                    #付费批改不存在
                    $data['message'] = '付费批改不存在';
                    $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
                }
                break;
            default:
                break;
        }
        $orderid = OrderinfoService::addOrderInfo($uid, $subjecttype, $fee, $ordertitle, $orderdesc, $mark, $teacheruid, $recommend_from, $devicetype, $groupbuyid, $coupon_price);
        if ($orderid) {
            //增加订单商品记录
            $data = $this->addOrderGoodsRec($orderid, $uid, $subjecttype, $subject_info, $isiospay);
            if ($subjecttype == 3) {
                $times = OrderinfoService::find()->select(['ctime'])->where(['orderid' => $orderid])->asArray()->one();
                StudioService::SetEnroll($uid, $classtypeid, $enrollid, $name, $mobile, $QQ, $school, $times['ctime']);
            }
            $data['orderid'] = $orderid;
            //返回结果
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
        }
    }

    /**
     * 增加订单商品记录
     * @param [type] $orderid          [description]
     * @param [type] $uid              [description]
     * @param [type] $subjecttype      [description]
     * @param [type] $subject_info_arr [description]
     */
    private function addOrderGoodsRec($orderid, $uid, $subjecttype, $subject_info_arr, $isiospay = 0) {
        $data['message'] = '';
        switch (intval($subjecttype)) {
            case 1:
                //直播
                $status = OrdergoodsService::addOrderGood($orderid, $uid, $subjecttype, $subject_info_arr['liveid'], $subject_info_arr['fee'], '');
                if ($status == 1) {
                    $data['message'] = "已购买" . $subject_info_arr['live_title'];
                } else if ($status == 3) {
                    $data['message'] = "写入失败！";
                }
                break;
            case 2:
                //课程
                if ($subject_info_arr) {
                    foreach ($subject_info_arr as $key => $coursevideo) {
                        //判断ios内购
                        if ($isiospay == 1) {
                            $cvideo_price = $coursevideo['ios_price'];
                        } else {
                            $cvideo_price = $coursevideo['sale_price'];
                        }
                        $status = OrdergoodsService::addOrderGood($orderid, $uid, $subjecttype, $coursevideo['coursevideoid'], $cvideo_price, '');
                        if ($status == 1) {
                            $data['message'] = "已购买" . $coursevideo['video_title'];
                            break;
                        } else if ($status == 3) {
                            $data['message'] = "写入失败！";
                            break;
                        }
                    }
                }
                break;
            case 3:
                //画室报名
                $status = OrdergoodsService::addOrderGood($orderid, $uid, $subjecttype, $subject_info_arr['enrollid'], $subject_info_arr['fee'], '');
                if ($status == 1) {
                    $data['message'] = "已购买" . $subject_info_arr['enroll_title'];
                    break;
                } else if ($status == 3) {
                    $data['message'] = "写入失败！";
                    break;
                }
                break;
            case 4:
                //打赏信息
                $status = OrdergoodsService::addOrderGood($orderid, $uid, $subjecttype, $subject_info_arr['subjectid'], $subject_info_arr['fee'], '');
                if ($status == 1) {
                    $data['message'] = "已购买" . $subject_info_arr['gift_name'];
                    break;
                } else if ($status == 3) {
                    $data['message'] = "写入失败！";
                    break;
                }
                break;
            case 5:
                #画室报名
                $status = OrdergoodsService::addOrderGood($orderid, $uid, $subjecttype, $subject_info_arr['correctid'], $subject_info_arr['fee'], '');
                if ($status == 1) {
                    $data['message'] = "已购买" . $subject_info_arr['content'];
                    break;
                } else if ($status == 3) {
                    $data['message'] = "写入失败！";
                    break;
                }
                break;
        }
        return $data;
    }

}
