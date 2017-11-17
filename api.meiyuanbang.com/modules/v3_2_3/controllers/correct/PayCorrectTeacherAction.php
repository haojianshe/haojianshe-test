<?php

namespace api\modules\v3_2_3\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 获取列表
 */
class PayCorrectTeacherAction extends ApiBaseAction {

    public function run() {
    	
        $request = Yii::$app->request;
        $data=[];
        $lastid=$this->requestParam('lastid');
        //筛选条件 /0/1/2/3 全部/素描/色彩/速写
        $type=$this->requestParam('type') ? $this->requestParam('type') :0;

        if(!$lastid){
        	//得到当前时段的付费批改老师
        	$teacher_now=UserCorrectService::getPayTeacherNow();
        	//当前时段繁忙的批改老师
        	$teacher_busy=UserCorrectService::getBusyTeacherNow();
        	//所有的付费批改老师
        	$all_teacher=UserCorrectService::getPayTeacher();


        	$online_teacher=array_diff($teacher_now,$teacher_busy);
        	$online_teacher_info=UserCorrectService::getPayCorrectTeacherInfo($online_teacher,0,$type);
        	
        	$rest_teacher=array_diff($all_teacher,$teacher_now);
        	$rest_teacher_info=UserCorrectService::getPayCorrectTeacherInfo($rest_teacher,2,$type);

        	$busy_teacher_info=UserCorrectService::getPayCorrectTeacherInfo($teacher_busy,1,$type);

        	$data=array_merge($online_teacher_info,$busy_teacher_info,$rest_teacher_info);
        }



        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

   
}
