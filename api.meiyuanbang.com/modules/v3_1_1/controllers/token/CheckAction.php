<?php
namespace api\modules\v3_1_1\controllers\token;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 用户token有效性检查表
 */
class CheckAction extends ApiBaseAction {

    public function run() {
        //本方法只需要返回有效状态，无效状态在filter中已经被检查
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }

}
