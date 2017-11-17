<?php
namespace api\modules\v3\controllers\user;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;

/**
 * 个人中心课程列表
 * @author ihziluoh
 *
 */
class CourseListAction extends ApiBaseAction{
   public  function run(){
        $rn = $this->requestParam('rn');
        if(!$rn){
            $rn = 10;
        }
        $uid = $this->requestParam('uid',true);
        $lastid = $this->requestParam('lastid');
        $courseids=CourseService::getTeacherCourseList($uid,$lastid,$rn);
        $ret=CourseService::getListDetail($courseids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}