<?php
namespace api\modules\v2_3_5\controllers\activity;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;
use api\service\CommentService;
/**
 * 问答回复列表
 */
class QaCmtReplyListAction extends ApiBaseAction
{
    public function run()
    {   
        $cid = $this->requestParam('cid',true); 
        $data=CommentService::getQaReplyCmtByCid($cid);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
