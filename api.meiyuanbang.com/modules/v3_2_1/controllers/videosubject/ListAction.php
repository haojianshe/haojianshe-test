<?php
namespace api\modules\v3_2_1\controllers\videosubject;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\VideoSubjectService;

/**
 * 获取一招分类列表
 */
class ListAction extends ApiBaseAction {

    public function run() {

        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $lastid = $this->requestParam('lastid');
        $subject_type = $this->requestParam('subject_type');
        $uid=$this->_uid;
        //获取一招id
        $videosubjectids=VideoSubjectService::getVideoSubjectList($lastid,$rn,$subject_type);
        $ret=VideoSubjectService::getVideoSubjectListInfo($videosubjectids,$uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
