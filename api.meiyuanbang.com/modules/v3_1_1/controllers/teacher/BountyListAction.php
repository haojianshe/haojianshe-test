<?php
namespace api\modules\v3_1_1\controllers\teacher;
use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\TeacherBountyService;
use api\service\UserDetailService;
use api\service\CorrectRewardService;
/**
 * 老师佣金记录表
 */
class BountyListAction extends ApiBaseAction {

    public function run() {
        //设置时区为北京
        date_default_timezone_set('PRC');  
        //开始时间
        $stime = $this->requestParam('stime');
        //结束时间
        $etime = $this->requestParam('etime');
        $rn = $this->requestParam('rn');
        if (!$rn) {
            $rn = 10;
        }
        $lastid = $this->requestParam('lastid');
        $uid=$this->_uid;
        $data['total_bounty']=0;
        $data['yesterday_bounty']=0;
        //第一页获取总金额
        if(!$lastid){
            //获取老师被用户打赏过的金额
            $teacher_reward = CorrectRewardService::getTotalReward($uid);
            $teacher_reward_yesterday = CorrectRewardService::getTotalReward($uid, strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)) ,strtotime(date('Y-m-d'.'00:00:00',time())));

            //佣金总额
            $data['total_bounty']=TeacherBountyService::getTotalBounty($uid)+$teacher_reward;
            //昨天佣金总额
            $data['yesterday_bounty']=TeacherBountyService::getTotalBounty($uid, strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)) ,strtotime(date('Y-m-d'.'00:00:00',time())))+$teacher_reward_yesterday;
        }
        
        //记录列表
        $list= TeacherBountyService::getList($uid,$stime,$etime,$lastid,$rn);
        //获取用户信息
        foreach ($list as $key => $value) {
            $list[$key]['userinfo']=UserDetailService::getByUid($value['uid']);
        }
        $data['list'] =$list;
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $data);
    }

}
