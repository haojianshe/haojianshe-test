<?php

namespace api\modules\v3_1_1\controllers\course;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseService;

/**
 * 得到推荐课程列表
 */
class RecommendCourseAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn');
        if (!$rn) {
            //没有传分页数量
            $rn = 50;
        }
        //批改id
        $lastid = $this->requestParam('lastid');
        #3免费 1收费
        $is_pay = $this->requestParam('is_pay', true);
        $f_catalog_id = $this->requestParam('f_catalog_id', true);
        //用户id
        $uid = $this->_uid;
        $s_catalog_id = $this->requestParam('s_catalog_id') ? $this->requestParam('s_catalog_id') : 0;
        $coursids = CourseService::getCourseRecommendList($f_catalog_id, $s_catalog_id, $lastid, $rn, $is_pay);
        $ret = CourseService::getListDetail($coursids, $uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
