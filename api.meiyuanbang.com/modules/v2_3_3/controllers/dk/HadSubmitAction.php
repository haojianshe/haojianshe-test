<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;
use api\service\DkCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 判断用户是否已经参加了该活动
 */
class HadSubmitAction extends ApiBaseAction {
    
    public function run() {
        //活动id
        $activityid = $this->requestParam('activityid', true);
        $submituid=$this->_uid;
        $model=DkCorrectService::findOne(['activityid'=>$activityid,"submituid"=>$submituid]);
        if($model){
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
        }else{
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
        }

    }

}
