<?php

namespace api\modules\v3_0_2\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\PosidHomeService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\SubjectDictService;

/**
 * 顶部二级分类
 */
class GetSubjectCategoryAction extends ApiBaseAction {

    public function run() {
        $ret = SubjectDictService::getSubjectMainType();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }
}
