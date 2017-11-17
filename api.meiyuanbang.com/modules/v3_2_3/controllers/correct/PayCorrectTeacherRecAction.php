<?php

namespace api\modules\v3_2_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;

use api\lib\enumcommon\ReturnCodeEnum;

/**
 */
class PayCorrectTeacherRecAction extends ApiBaseAction {

    public function run() {
        $request = Yii::$app->request;
         //筛选条件 /0/1/2/3 全部/素描/色彩/速写
        $type=$this->requestParam('type') ? $this->requestParam('type') :0;

        //当前值班老师
       	$teacher_now=UserCorrectService::getPayTeacherNow();
        //当前繁忙老师
        $teacher_busy=UserCorrectService::getBusyTeacherNow();
        //可以接受付费批改的老师
        $teacheruids=array_diff($teacher_now,$teacher_busy);
        //获取老师信息
        $data=UserCorrectService::getPayCorrectTeacherInfo($teacheruids,0,$type);
        //若大于三人随机取三个
       	if(count($data)>3){
       		shuffle($data);
       		$data=array_slice($data,0,3);
       	}
        
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
