<?php
namespace api\modules\v1_2\controllers\correct;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 学生批改列表
 */
class CorrectTeacherInfoAction extends ApiBaseAction
{
    public function run()
    {       
        $uid=$this->requestParam('uid',true);
        $data=UserCorrectService::getUserCorrectDetail($uid); 
        //付费批改老师繁忙状态
        if(intval($data['correct_fee'])>0){
            //得到当前时段的付费批改老师
            $teacher_now=UserCorrectService::getPayTeacherNow();
            //当前时段繁忙的批改老师
            $teacher_busy=UserCorrectService::getBusyTeacherNow();
            //当前可批改的付费老师
            $online_teacher=array_diff($teacher_now,$teacher_busy);
            if(!in_array($uid,$online_teacher)){
                $data['status']=2;
            }
        }
        //2.3.5增加繁忙状态,暂时返回休息状态
        /*if($data['status']==3){
        	$data['status']=2;
        }  */            
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
