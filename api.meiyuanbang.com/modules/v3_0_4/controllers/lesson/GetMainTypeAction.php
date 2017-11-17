<?php
namespace api\modules\v3_0_4\controllers\lesson;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 获取跟着画一级分类
 *
 */
class GetMainTypeAction extends ApiBaseAction {

    public function run() {
        $mainlist = DictdataService::getLessonMainType();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$mainlist);   
       
    }
    
}
