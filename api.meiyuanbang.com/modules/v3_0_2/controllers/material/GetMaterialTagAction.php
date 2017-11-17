<?php

namespace api\modules\v3_0_2\controllers\material;

use Yii;
use api\components\ApiBaseAction;
#use api\service\PosidHomeService;
use api\service\MaterialSubjectService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\SubjectDictService;

/**
 * 选择素材tag
 */
class GetMaterialTagAction extends ApiBaseAction {

    public function run() {
         $f_catalog_id = $this->requestParam('f_catalog_id',true);
         $s_catalog_id = $this->requestParam('s_catalog_id',true);
        $ret = MaterialSubjectService::getMaterialTag($f_catalog_id,$s_catalog_id);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }
}
