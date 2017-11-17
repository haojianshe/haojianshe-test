<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;
use api\service\CommentService;
/**
 * 问答详情
 */
class QaCmtAction extends ApiBaseAction
{
    public function run()
    {   
        $newsid = $this->requestParam('newsid',true); 
        $last_cid = $this->requestParam('last_cid'); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'):10;
        $data=CommentService::getQaCmtList($newsid,$last_cid,$rn);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
