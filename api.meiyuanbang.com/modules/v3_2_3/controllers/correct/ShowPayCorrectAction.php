<?php

namespace api\modules\v3_2_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 是否展示付费批改
 */
class ShowPayCorrectAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
        $ret=UserCorrectService::getPayTeacherNow();
        if($ret){
            $data['is_show']=1; 
        }else{
            $data['is_show']=0; 
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
