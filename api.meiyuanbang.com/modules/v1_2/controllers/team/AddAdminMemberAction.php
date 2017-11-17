<?php
namespace api\modules\v1_2\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 增加管理员
 */
class AddAdminMemberAction extends ApiBaseAction
{   
    public function run()
    {  
        $teamid=$this->requestParam('teamid',true);
        $uid=$this->requestParam('a_uid',true);
        $model=TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$uid]);
        //用户有加入小组 并且不是管理员
        if($model->uid==$uid && $model->isadmin==0 ){
            $model->isadmin=1;
            $res=$model->save();
            $data['uid']=$uid;
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
        }else{
            $data['message']='已经是管理员';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE,$data);
        }
    }
}
