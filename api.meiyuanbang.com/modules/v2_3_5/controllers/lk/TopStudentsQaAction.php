<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;
use api\service\LkMaterialRelationService;

/**
 * 状元分享会列表
 */
class TopStudentsQaAction extends ApiBaseAction
{
    public function run()
    {   
        $lkid = $this->requestParam('lkid',true); 
        $last_newsid = $this->requestParam('last_newsid'); 
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
        $newsids=LkMaterialRelationService::getArticleList($lkid,1,$last_newsid,$rn);
        $data=LkMaterialRelationService::getAllLkArticleInfo($lkid,$newsids);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        
    }
}
