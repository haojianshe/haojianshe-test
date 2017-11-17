<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;
use common\models\myb\DkCorrect;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\DkCorrectService;
/**
 */
class DkCorrectAction extends ApiBaseAction {
    
    public function run() {
        //获取活动
        $activityid = $this->requestParam('activityid', true); 
        //分页   
        $lastid = $this->requestParam('lastid')?$this->requestParam('lastid'):0;
        $rank = $this->requestParam('rank')?$this->requestParam('rank'):0;
        $rn = $this->requestParam('rn')?$this->requestParam('rn'):10;
        $data['dkcorrect']=DkCorrectService::getDkCorrectList($activityid,$lastid,$rank,$rn);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }

}
