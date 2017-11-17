<?php

namespace api\modules\v3_2_3\controllers\teacher;

use Yii;
use api\components\ApiBaseAction;
use api\service\CorrectRewardService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取奖品列表
 */
class RewardListAction extends ApiBaseAction {

    public function run() {
        $rn = $this->requestParam('rn') ? $this->requestParam('rn') : 10;
        $lastid = $this->requestParam('lastid');
        //获取奖品列表
        $correctRewardList = CorrectRewardService::getTeacherRewardList($this->_uid,$rn,$lastid);
        if (empty($correctRewardList)) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        } else {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $correctRewardList);
        }
    }

}
