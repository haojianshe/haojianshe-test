<?php
namespace api\controllers\teacher;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\service\UserCorrectService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 名师列表获取最新
 */
class GetNewAction extends ApiBaseAction
{   
    public function run()
    { 
        $request=Yii::$app->request;
        $uid=$request->get('uid');
        $last_id=$this->requestParam('last_id');
        $teacherids=UserDetailService::getTeacherList($last_id,10);
        $usercorrect_count=UserCorrectService::getUserCorrectCount();
        if(count($teacherids)<1){
            $data['list']=array();
            $data['correct_teacher_count']=$usercorrect_count;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data); 
        }
        $data['list']=UserDetailService::getTeacherInfo($this->_uid,$teacherids);
        $data['correct_teacher_count']=$usercorrect_count;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data); 
    }
}
