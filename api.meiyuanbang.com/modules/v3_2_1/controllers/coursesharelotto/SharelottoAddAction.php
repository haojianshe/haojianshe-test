<?php
namespace api\modules\v3_2_1\controllers\coursesharelotto;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseSharelottoService;

/**
 * 增加分享记录
 */
class SharelottoAddAction extends ApiBaseAction {

    public function run() {
        //分享渠道
        $type = $this->requestParam('type', true);
        //课程id
        $courseid = $this->requestParam('courseid', true);
        $uid=$this->_uid;
        $ret=CourseSharelottoService::addSharelotto($courseid,$uid,$type);
        switch ($ret) {
            case '0':
                $this->controller->renderJson(ReturnCodeEnum::MYSQL_ERR_INSERT);
                break;
            case '1':
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
                break;
            case '2':
                $data['message']="已经抽过奖";
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
                break;
            default:
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
                break;
        }
    }

}
