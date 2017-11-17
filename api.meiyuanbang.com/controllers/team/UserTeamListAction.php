<?php
namespace api\controllers\team;

use Yii;
use api\components\ApiBaseAction;
use api\service\TeamMemberService;
use api\service\TeamInfoService;
use api\service\UserDetailService;
use api\lib\enumcommon\ReturnCodeEnum;
/**
 * 获取用户加入的小组列表
 */
class UserTeamListAction extends ApiBaseAction
{
    public function run()
    {
        $request=Yii::$app->request;
        $uid=$this->_uid;
        //加入时间
        $addtime=$this->requestParam('addtime');
        //分页
        $rn=$this->requestParam('rn');
        if(!isset($rn)){
            $rn=5;
        }
        //获取小组id 及加入时间（用于排序）
        $teamids=TeamMemberService::getTeamidsByUid($uid,$addtime,$rn);
        
        //如果是获取最新 增加自己的小组
        if(!isset($addtime) || $addtime=null){
            if(TeamInfoService::getTeaminfoByuid($uid)['teamid']){
                $teaminfo_u=TeamInfoService::getTeaminfoByuid($uid);
                //获取管理员加入的时间 加入到列表中
                $admin=TeamMemberService::findOne(['teamid'=>$teaminfo_u['teamid'],'uid'=>$uid]);
                $admin_add['teamid']=$teaminfo_u['teamid'];
                $admin_add['addtime']=$admin['addtime'];
                unset($teamids[array_search($admin_add,$teamids)]);
                array_unshift($teamids,$admin_add);
            }
        }
        foreach ($teamids as $key => $value) {
                //获取小组基本信息
                $teaminfo=TeamInfoService::getUserTeamInfo($value['teamid']);
                $teaminfo['isadmin']=0;
                //返回用户加入时间用于排序
                $teaminfo['addtime']=$value['addtime'];
                $data[]=array_merge(UserDetailService::getByUid($teaminfo['uid']),$teaminfo);
        }
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
    }
}
