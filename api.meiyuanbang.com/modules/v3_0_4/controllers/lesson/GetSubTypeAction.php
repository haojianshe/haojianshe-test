<?php
namespace api\modules\v3_0_4\controllers\lesson;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 获取跟着画二级分类
 *
 */
class GetSubTypeAction extends ApiBaseAction {

    public function run() {
        $maintypeid = $this->requestParam('maintypeid',true);
        $subtype = DictdataService::getLessonSubType($maintypeid);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$subtype);   
    }

}
