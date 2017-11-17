<?php
namespace api\modules\v3_2_1\controllers\coursesharelotto;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\CourseSharelottoService;

/**
 * 是否显示抽奖
 */
class ShowSharelottoAction extends ApiBaseAction {

    public function run() {
        $courseid = $this->requestParam('courseid', true);
        $uid=$this->_uid;
        $data['game_show']=CourseSharelottoService::IsShowPrize($uid, $courseid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
