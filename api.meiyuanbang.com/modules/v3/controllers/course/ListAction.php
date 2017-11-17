<?php
namespace api\modules\v3\controllers\course;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;

/**
 * 课程分类获取数据列表
 * @author ihziluoh
 *
 */
class ListAction extends ApiBaseAction{
   public  function run(){
        $rn = $this->requestParam('rn');
        if(!$rn){
            $rn = 10;
        }
        //批改id
        $lastid = $this->requestParam('lastid');
        $f_catalog_id = $this->requestParam('f_catalog_id',true);
        $s_catalog_id = $this->requestParam('s_catalog_id')? $this->requestParam('s_catalog_id'):0;
        $uid=$this->_uid;
        $coursids=CourseService::getCourseList($f_catalog_id,$s_catalog_id,$lastid,$rn);
        $ret=CourseService::getListDetail($coursids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}