<?php

namespace api\modules\v3_0_4\controllers\course;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
#use common\service\DictdataService;
use api\service\CourseService;
use api\service\LiveService;

/**
 * 得到课程相关信息
 */
class RelatedAction extends ApiBaseAction {

    public function run() {
        $courseid = $this->requestParam('courseid', true); //课程id
        $f_catalog_id = $this->requestParam('f_catalog_id', true); //主分类
        $s_catalog_id = $this->requestParam('s_catalog_id', true); //二级分类
        #精彩课程推荐
        $ret['course_recommend'] = CourseService::getRelatedCourse($courseid, $f_catalog_id, $s_catalog_id,$this->_uid); //获取相关课程 推荐课程
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        $teacherid = $this->requestParam('teacherid', true);
        $lastid = $this->requestParam('lastid');

         //获取老师其他的课程
        $courseids = CourseService::getTeacherCourseList($teacherid, $lastid, $rn); //获取老师的课程
        $res = CourseService::getListDetail($courseids,$this->_uid);
        if ($res) {
            $array = [];
            $arr = [];
            foreach ($res as $key => $val) {
                if ($s_catalog_id == $val['s_catalog_id']) {
                    $array[$key] = $val;
                } else {
                    $arr[$key] = $val;
                }
            }
            $ret['course_list'] = array_merge($array, $arr);
        }
        #老师的其他直播
        $liveids = LiveService::getTeacherLiveList($teacherid, $lastid, 2);
        $ret['live_list'] = LiveService::getListDetail($liveids);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
