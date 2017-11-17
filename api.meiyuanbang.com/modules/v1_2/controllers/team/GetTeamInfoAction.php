<?php
namespace api\modules\v1_2\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取小组信息,此版本返回值加入是否需要密码的信息
 */
class GetTeamInfoAction extends ApiBaseAction
{	
    public function run()
    {
          $uid=$this->_uid;
          $team_uid=$this->requestParam('team_uid',true);
          $data=TeamInfoService::getTeaminfoByuid($team_uid);
          $isuser=TeamInfoService::isTeamUser($uid,$data['teamid']);
          //判断是否加入小组
          if($isuser){
            $data['isadmin']=0;
          }else{
            $data['isadmin']=-1;
          }
          //返回是否是管理员
          $isadmin=TeamInfoService::isTeamAdmin($uid,$data['teamid']);
          if($isadmin){
            $data['isadmin']=1;
          }
          //判断是否是小组创建者
          if($uid==$team_uid){
             $data['isadmin']=2;
          }
          //获取小组信息里的最新加入成员列表（10个）
          $members_list=TeamInfoService::getTeamInfoNewuser($data['teamid']);
          //默认第一个显示群主
          if (count($members_list)<=0) {
              $members_list[]=$data['uid'];
          }else{
              array_unshift($members_list,$data['uid']);
          } 
          foreach ($members_list as $key => $value) {
              $data['members_list'][$key]= UserDetailService::getByUid($value);
          }
          $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
