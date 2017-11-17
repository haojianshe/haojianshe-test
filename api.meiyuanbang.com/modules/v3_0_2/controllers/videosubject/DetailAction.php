<?php
namespace api\modules\v3_0_2\controllers\videosubject;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\VideoSubjectService;
use api\service\VideoSubjectItemService;
use api\service\CourseService;
/**
 * 视频专题列表
 * @author ihziluoh
 *
 */
class DetailAction extends ApiBaseAction{
   public  function run(){
        $subjectid=$this->requestParam('subjectid',true);
        //获取课程专题详情
        $data=VideoSubjectService::getDetail($subjectid);
        //获取课程专题下课程列表信息
        $courseids_arr=explode(",", $data['courseids']);
        $data['course_list']=CourseService::getListDetail($courseids_arr,$this->_uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}