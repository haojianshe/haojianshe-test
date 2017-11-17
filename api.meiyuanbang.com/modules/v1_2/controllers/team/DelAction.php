<?php
namespace api\modules\v1_2\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 删除小组用户*/
class DelAction extends ApiBaseAction
{   
    public function run()
    {
        $teamid=$this->requestParam('teamid',true);
        $del_uid=$this->requestParam('del_uid',true);
        $model_teaminfo=TeamInfoService::getTeaminfoByteamid($teamid);
        //判断是否是管理员
        $isadmin=TeamInfoService::isTeamAdmin($this->_uid,$teamid);
        if($model_teaminfo['uid']==$this->_uid ||  $isadmin){
            $del_teammember=TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$del_uid]);
            if($del_teammember){
                $findteaminfo=TeamInfoService::findOne(['teamid'=>$teamid]);
                if($findteaminfo->membercount>0){
                        $findteaminfo->membercount=$findteaminfo->membercount-1;
                        $findteaminfo->save();
                }
                $del_teammember->delete();
                $data['del_uid']=$del_uid;
                $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
            }else{
                $data['message']='用户不存在';
                $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL,$data);
            }
        }
        $data['message']='无权限操作';
        $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL,$data);
    }
}
