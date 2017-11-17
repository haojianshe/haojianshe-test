<?php

namespace api\modules\v3_0_1\controllers\studio;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\StudioService;


/**
 * 得到班型下面的报名方式
 *
 */
class GetClassTypeCAction extends ApiBaseAction {

    public function run() {
        //班型id
        $classtypeid = $this->requestParam('classtypeid'); //班型id
        //用户id
        $uid = $this->requestParam('uid');
        if ($uid > 0) {
            $ret = StudioService::getStudioClassType($uid, $classtypeid);
        } else {
            $ret = [];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }

}
