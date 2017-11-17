<?php
namespace api\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 取消管理员
 */
class DelAdminMemberAction extends ApiBaseAction
{   
    public function run()
    {
        $teamid=$this->requestParam('teamid',true);
        $uid=$this->requestParam('a_uid',true);
        $model=TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$uid]);
        //判断是否是管理员
        if($model->uid==$uid && $model->isadmin==1){
            $model->isadmin=0;
            $res=$model->save();
            if($res){
                $data['uid']=$uid;
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
                }else{
                   $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
                }    
        }else{
               $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE);
        } 
    }
}
