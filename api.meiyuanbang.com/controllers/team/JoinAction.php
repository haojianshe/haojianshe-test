<?php
namespace api\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\lib\myb\enumcommon\CointaskTypeEnum;
use common\service\dict\CointaskDictService;
use common\models\myb\Cointask;
use api\service\UserCoinService;
use api\service\CointaskService;
/**
 * 加入小组
 */
class JoinAction extends ApiBaseAction
{   
    public function run()
    {
        $teamid=$this->requestParam('teamid',true);
        $model=new TeamMemberService();
        $model->uid=$this->_uid;
        $model->teamid=$teamid;
        $model->addtime=time();
        $model->isadmin=0;
        //判断是否已加入
        if(TeamMemberService::findOne(['teamid'=>$teamid,'uid'=>$this->_uid])){
            $data['message']='已经加入小组';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_ERR_RESPONSE,$data);
        }        
        $model_teaminfo=TeamInfoService::findOne(['teamid'=>$teamid]);
        $model_teaminfo->membercount=$model_teaminfo->membercount+1;
        $model_teaminfo->save();
        $model->save();
        $data['uid']=$this->_uid;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}