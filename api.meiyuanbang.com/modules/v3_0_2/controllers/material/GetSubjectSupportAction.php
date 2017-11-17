<?php

namespace api\modules\v3_0_2\controllers\material;

use Yii;
use api\components\ApiBaseAction;
use api\service\MaterialSubjectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 专题点赞
 */
class GetSubjectSupportAction extends ApiBaseAction {

    public function run() {
        $subjectid = $this->requestParam('subjectid', true);
        $data = MaterialSubjectService::addZan($subjectid);
        if(empty($data)){
            $data =[
                'data'=>0
            ];
        }else{
           $data =[
                'data'=>1
            ]; 
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }
}
