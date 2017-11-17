<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;
/**
 * 问答详情
 */
class QaAction extends ApiBaseAction
{
    public function run()
    {   
        $newsid = $this->requestParam('newsid',true); 
        $data=ActivityQaService::getQaDetail($newsid,$this->_uid);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
