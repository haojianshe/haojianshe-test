<?php
namespace api\modules\v1_2\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 编辑小组
 */
class TeamEditAction extends ApiBaseAction
{
    public function run()
    {
        $teamid=$this->requestParam('teamid',true);
        //增加判断是否是管理员 或者群主
        $model_teaminfo=TeamInfoService::findOne(['teamid'=>$teamid]);
        //$model_teaminfo=TeamInfoService::getTeaminfoByteamid($teamid);
        $isadmin=TeamInfoService::isTeamAdmin($this->_uid,$teamid);
        //$model_teammember=TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$this->_uid]);
        if($model_teaminfo->uid==$this->_uid || $isadmin){
            //赋值
            if($this->requestParam('teamname')){
                $model_teaminfo->teamname=$this->requestParam('teamname');
            }
            if($this->requestParam('backurl')){
                $model_teaminfo->backurl=$this->requestParam('backurl');
            }
            if($this->requestParam('notice')){
                $model_teaminfo->notice=$this->requestParam('notice');
                $model_teaminfo->noticetime=time();
            }
            if($this->requestParam('password')){
                $model_teaminfo->password=$this->requestParam('password');
            }
            $model_teaminfo->save();
            $data['teamid']=$teamid;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);  
        }
        $data['message']='无权限操作';
        $this->controller->renderJson(ReturnCodeEnum::ERR_USER_ILLEGAL,$data);
    }
}
