<?php

namespace api\modules\v3_2_3\controllers\reward;

use Yii;
use api\components\ApiBaseAction;
use common\service\dict\CorrectGiftService;
use api\service\CorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取打赏奖品列表
 */
class ListAction extends ApiBaseAction {

    public function run() {
        //老师id
        $teacheruid = $this->requestParam('teacheruid', true);
        //获取奖品列表
        $data['content'] = CorrectGiftService::getGiftData();
        $data['correct_num'] = CorrectService::getTeacherSetUserCorrect($this->_uid, $teacheruid);
        if (empty($data)) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
        }
        #CorrectRewardService::pushGiftMessage(60504,46273,16);
    }

}
