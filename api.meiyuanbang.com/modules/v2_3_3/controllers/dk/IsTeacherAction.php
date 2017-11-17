<?php
namespace api\modules\v2_3_3\controllers\dk;
use Yii;
use api\components\ApiBaseAction;

use api\lib\enumcommon\ReturnCodeEnum;
use common\models\myb\UserCorrect;

/**
 * 判断是否是批改老师
 */
class IsTeacherAction extends ApiBaseAction {
    public function run() {
        //用户id
        $teacher=UserCorrect::findOne(["uid"=>$this->_uid]);
        if($teacher){
            if($teacher->isactivity==1){
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
            }
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_REQUEST);
   }

}
