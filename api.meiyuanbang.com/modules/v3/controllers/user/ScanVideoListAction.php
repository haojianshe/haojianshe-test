<?php
namespace api\modules\v3\controllers\user;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ScanVideoRecordService;
use api\service\LiveService;
use api\service\CourseService;

/**
 * 最新学习列表
 * @author ihziluoh
 *
 */
class ScanVideoListAction extends ApiBaseAction{
   public  function run(){
        $rn = $this->requestParam('rn');
        if(!$rn){
            $rn = 10;
        }
        $uid = $this->_uid;
        $lastid = $this->requestParam('lastid');
        //获取观看记录id列表
        $recordids=ScanVideoRecordService::getScanVideoList($uid,$lastid,$rn);
        $ret_list=[];
        foreach ($recordids as $key => $value) {
            //获取观看记录详情
            $scan_item=ScanVideoRecordService::getDetail($value);
            //分别获取直播、课程详情
            $scan_item['course_info']=(object)null;
            $scan_item['live_info']=(object)null;
            //类型：1=>直播,2=>课程
            switch (intval($scan_item['subjecttype'])) {
                case 1:
                    $scan_item['live_info']=LiveService::getDetail($scan_item['subjectid']);
                    break;
                case 2:
                    $scan_item['course_info']=CourseService::getDetail($scan_item['subjectid'],$uid);
                    break;
                default:
                    break;
            }
            $ret_list[]=$scan_item;
        }
        $ret['total_count']=ScanVideoRecordService::getScanCount($uid);
        $ret['list']=$ret_list;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}