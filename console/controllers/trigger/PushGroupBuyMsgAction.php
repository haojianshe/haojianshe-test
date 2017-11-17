<?php
namespace console\controllers\trigger;
use Yii;
use yii\base\Action;
use console\service\GroupBuyService;
use console\service\OrderinfoService;

/**
 * 启动pushservice守护进程
 */
// */1 * * * *   /home/web/backcode/pushservice trigger/pushgroupbuymsg &
class PushGroupBuyMsgAction extends Action
{
    public function run()
    {
    	$groupbuyarr=GroupBuyService::getEndGroupBuy();
        if($groupbuyarr){
            foreach ($groupbuyarr as $key => $value) {
               $uid_arr=OrderinfoService::getGroupBuyUser($value['groupbuyid']);
               if($uid_arr){
                    GroupBuyService::groupBuyPushMsg($uid_arr, $value['title']);
               }
               GroupBuyService::updateNoticeStatus($value['groupbuyid']);
            }
        }
        
    }    
    
}