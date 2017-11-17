<?php
namespace api\modules\v3\controllers\order;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\OrderactionService;
use api\service\OrdergoodsService;
use api\service\OrderinfoService;
use api\service\LiveService;
use api\service\CourseSectionVideoService;
use api\service\CourseSectionService;


/**
 * 个人中心课程列表
 * @author ihziluoh
 *
 */
class GetInfoAction extends ApiBaseAction{
   public  function run(){
        $orderid=$this->requestParam('orderid',true);
        $uid=$this->_uid;
        $orderinfo=OrderinfoService::getOrderDetail($orderid);
        if($orderinfo){
            //判断是否是当前用户
            if($orderinfo['uid']!=$uid){
                $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
            }
            $subjectids=OrdergoodsService::getOrderGoodsDetail($orderid,$uid,$orderinfo['subjecttype']);
            //1 直播，2 课程
            $subject_info=[];
            switch (intval($orderinfo['subjecttype'])) {
                case 1:
                    foreach ($subjectids as $key => $value) {
                        $subject_info[]=LiveService::getDetail($value['subjectid'],$uid);
                    }
                    break;
                case 2:
                    foreach ($subjectids as $key => $value) {
                        $course_video=CourseSectionVideoService::getDetail($value['subjectid'],$uid);
                        $section_info=CourseSectionService::getDetail($course_video['sectionid']);
                        $subject_info[]=array_merge($section_info,$course_video);
                    }
                    break;
            }
            $orderinfo['goods']=$subject_info;
        }else{
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$orderinfo);
    }
}