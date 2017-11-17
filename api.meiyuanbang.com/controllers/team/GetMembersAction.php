<?php
namespace api\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取加入小组用户
 */
class GetMembersAction extends ApiBaseAction
{   
    public function run()
    { 
        $teamid=$this->requestParam('teamid',true);
        $last_id=$this->requestParam('last_id');
        $rn=$this->requestParam('rn');
        if(!isset($last_id)){
          $last_id=0;
        }
        if(!isset($rn)){
          $rn=10;
        }
        $uids=TeamInfoService::getList($teamid,$rn,$last_id); 
        foreach ($uids as $key => $value) {
            $data[$key]= UserDetailService::getByUid($value['uid']);
            $data[$key]['isadmin']= $value['isadmin'];
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
