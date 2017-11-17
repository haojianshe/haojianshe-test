<?php
namespace api\modules\v1_2\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 退出小组
 */
class OutAction extends ApiBaseAction
{   
    public function run()
    {
        $teamid=$this->requestParam('teamid',true);
        $is_team_user=TeamInfoService::isTeamUser($this->_uid,$teamid);
        $is_admin_user=TeamInfoService::isTeamAdmin($this->_uid,$teamid);
        //$model=TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$this->_uid]);
        if($is_admin_user || $is_team_user){
            $model_teaminfo=TeamInfoService::findOne(['teamid'=>$teamid]);
            //更改小组人数
            $model_teaminfo->membercount=$model_teaminfo->membercount-1;
            $model_teaminfo->save();
            //删除加入小组记录
            $model=TeamMemberService::findOne(["teamid"=>$teamid,"uid"=>$this->_uid]);
            $model->delete();
            $data['uid']=$this->_uid;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }else{
            $data['message']='未加入小组';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE,$data);
        }
    }
}
