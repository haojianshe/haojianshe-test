<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\CorrectChangeReasonService;

/**
 * 转作品理由列表
 */
class ChangeReasonAction extends ApiBaseAction
{
    public function run()
    {       
    	$ret = CorrectChangeReasonService::getReasonList();    	
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
