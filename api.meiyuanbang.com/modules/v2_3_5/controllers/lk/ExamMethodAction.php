<?php
namespace api\modules\v2_3_5\controllers\lk;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityArticleService;
use api\service\LkMaterialRelationService;


/**
 * 联考攻略列表
 */
class ExamMethodAction extends ApiBaseAction
{
    public function run()
    {   
        $lkid = $this->requestParam('lkid',true); 
        //最后一个id
        $last_newsid = $this->requestParam('last_newsid'); 
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10; 
        // 类型 1/2/3 状元分享会/名师大讲堂/联考攻略
        $newsids=LkMaterialRelationService::getArticleList($lkid,3,$last_newsid,$rn);
        $data=LkMaterialRelationService::getAllLkArticleInfo($lkid,$newsids);
        return $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
