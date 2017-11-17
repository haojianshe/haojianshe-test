<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\PosidHomeUserService;

/**
 * 得到画室广告
 *
 */
class GetStudioAdvAction extends ApiBaseAction {

    public function run() {
        $uid = $this->requestParam('uid', true); #画室id
        $ret = PosidHomeUserService::getStudioPosid($uid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }
}
