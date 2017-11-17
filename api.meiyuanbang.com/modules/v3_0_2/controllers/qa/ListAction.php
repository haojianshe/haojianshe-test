<?php
namespace api\modules\v3_0_2\controllers\qa;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\ActivityQaService;

/**
 * 问答列表
 * @author ihziluoh
 *
 */
class ListAction extends ApiBaseAction{

   public  function run(){
        $rn=$this->requestParam('rn') ? $this->requestParam('rn'): 10;
        $lastid=$this->requestParam('lastid');
        //获取问答id
        $qaids=ActivityQaService::getAllQaList($lastid,$rn);
        //获取问答信息
        $data=ActivityQaService::getAllQaInfo($qaids);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
    
}