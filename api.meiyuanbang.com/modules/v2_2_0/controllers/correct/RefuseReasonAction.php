<?php
namespace api\modules\v2_2_0\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\dict\CorrectRefuseReasonService;

/**
 * 拒批理由列表
 */
class RefuseReasonAction extends ApiBaseAction
{
    public function run()
    {   
    	$ret = CorrectRefuseReasonService::getReasonList();    	
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);    	
    }
}
