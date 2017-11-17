<?php
namespace api\controllers\teacher;

use Yii;
use api\components\ApiBaseAction;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;

/**
 * 名师列表获取老数据
 */
class GetOldAction extends ApiBaseAction
{
    public function run()
    {
        $request=Yii::$app->request;
        $uid=$request->get('uid');
        $last_id=$this->requestParam('last_id',true);
        $teacherids=UserDetailService::getTeacherList($last_id,10);
        if(count($teacherids)<1){
            $data['list']=[];
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }
        $data['list']=UserDetailService::getTeacherInfo($this->_uid,$teacherids);
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
