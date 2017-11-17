<?php
namespace api\modules\v3\controllers\course;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;
use api\service\ScanVideoRecordService;
use common\lib\myb\enumcommon\InfoCollectionSubjectTypeEnum;
use common\service\myb\InfocollectionVisitService;

/**
 * 课程详情
 * @author ihziluoh
 *
 */
class GetInfoAction extends ApiBaseAction{
   public  function run(){
        //课程id
        $courseid = $this->requestParam('courseid',true);
        //非浏览 默认增加浏览数 值非0 则不加
        $notbrowse=$this->requestParam('notbrowse') ? $this->requestParam('notbrowse'): 0;
        if($notbrowse==0){
            CourseService::addHits($courseid);
        }
        $uid=$this->_uid;
        $detail=CourseService::getFullCourseInfo($courseid,$uid);
     
        if($uid>0){
        	//增加观看记录类型：1=>直播,2=>课程
        	ScanVideoRecordService::addScanRecord($uid,2,$courseid);
        }
        //指定用户记录视频课程被访问信息
        InfocollectionVisitService::writeVisitRecord($this->_uid, $detail['teacheruid'], InfoCollectionSubjectTypeEnum::COURSE);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$detail);
    }
}