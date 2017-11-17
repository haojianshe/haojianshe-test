<?php

namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LkMaterialRelationService;

/**
 * 查询图片
 */
class GetPicAction extends ApiBaseAction {

    public function run() {
        $typeArr = [
            'sm',
            'sc',
            'sx'
        ];
        $type = $this->requestParam('type', true);
        $offset = $this->requestParam('rn');
        if (!in_array($type, $typeArr) || $offset < 1) {
            return $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST, $data);
        }
        //便宜量
        $data = LkMaterialRelationService::getPicList($type, $offset);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
