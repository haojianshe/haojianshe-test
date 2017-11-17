<?php

namespace api\modules\v3_0_4\controllers\live;

use Yii;
use api\components\ApiBaseAction;
//use api\service\PosidHomeService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\LiveDictService;

/**
 * 顶部二级分类
 */
class GetLiveCategoryAction extends ApiBaseAction {

    public function run() {
        $ret = LiveDictService::getCorrectMainType();
        $array =['推荐'];
        $ret = ($array+$ret);
        foreach ($ret as $k => $v) {
            $arr['s_catalog'][] = [
                'id' => $k,
                'name' => $v
            ];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $arr);
    }

}
