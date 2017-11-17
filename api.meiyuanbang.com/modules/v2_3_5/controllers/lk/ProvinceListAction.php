<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\LkService;

/**
 * 联考城市列表
 */
class ProvinceListAction extends ApiBaseAction
{
    public function run()
    {
        $data=LkService::getProvinceList();
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
