<?php

namespace api\modules\v3_2_3\controllers\user;

use Yii;
use api\components\ApiBaseAction;
use api\service\RegionService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 根据省获取市、区、县 
 */
class RegionInfoAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
        //省的id
        $provinceid = $this->requestParam('provinceid', true);
        //市 区id
        $region_id = $this->requestParam('city_id');
        $provinceid = intval($provinceid);

        if (intval($provinceid) > 34 || intval($provinceid) <= 0) {
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }
        $region = '';
        if (!$region_id) {
            $region = RegionService::getUserRegionInfo($provinceid);
        } else {
          
            $region = RegionService::getUserRegionInfo($region_id,1);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $region);
    }

}
