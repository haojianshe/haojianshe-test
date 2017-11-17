<?php
namespace api\modules\v3_2_1\controllers\videosubject;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\VideoSubjectService;
use api\service\CourseService;

/**
 * 获取最新加入一招的课程
 */
class GetNewAction extends ApiBaseAction {

    public function run() {

        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $lastid = $this->requestParam('lastid');
        $uid=$this->_uid;
        //获取课程id
        $courseids=VideoSubjectService::getNewCourseidRedis($lastid,$rn);
		$ret=CourseService::getListDetail($courseids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
