<?php
namespace api\modules\v3_2_1\controllers\videosubject;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\VideoSubjectDictDataService;
/**
 * 获取最新加入一招的课程
 */
class CatalogAction extends ApiBaseAction {

    public function run() {
        $ret=VideoSubjectDictDataService::getCatalog();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }
    
}
