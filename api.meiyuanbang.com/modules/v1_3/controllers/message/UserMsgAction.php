<?php
namespace api\modules\v1_3\controllers\message;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MessageService;
use api\service\UserDetailService;
use common\service\CommonFuncService;

/**
 * 获取私信列表
 * 与老版本相比增加了分页功能
 */
class UserMsgAction extends ApiBaseAction
{
    public function run()
    {
    	$rn = $this->requestParam('rn');
    	if(!$rn){
    		//新版支持分页
    		$rn = 10;
    	}
    	//分页
    	$lastmid = $this->requestParam('lastmid');
    	if(!$lastmid){
    		$lastmid = 0;
    	}
    	$ret['content']=[];
    	//按时间获取发送过私信的人的最后一条私信
    	$mids = MessageService::getMidsByUid($this->_uid, $lastmid, $rn);
    	$msgs = MessageService::getByMids($mids);
    	foreach($msgs as $k=>$v){
    		//取得通信人的id
    		if($v['from_uid']==$this->_uid){
    			$otheruid = $v['to_uid'];
    		}
    		else{
    			$otheruid = $v['from_uid'];
    		}
    		//处理语音和图片
    		if($v['mtype']==1){
    			$v['content'] = '[图片]';
    		}
    		else if($v['mtype']==2){
    			$v['content'] = '[语音]';
    		}
    		//时间
    		$v['ctime'] = CommonFuncService::format_time($v['ctime']);
    		//检查是否有新消息
    		if(MessageService::checkNewMsg($this->_uid, $otheruid)>0){
    			$v['has_new_msg'] = 1;
    		}
    		else{
    			$v['has_new_msg'] = 0;
    		}
    		//添加对方用户信息
    		$user = UserDetailService::getByUid($otheruid);
    		$v['sname'] = $user['sname'];
    		$v['avatar'] = $user['avatar'];
    		$v['ukind'] = $user['ukind'];
    		$v['ukind_verify'] = $user['ukind_verify'];
    		$ret['content'][] = $v;
    	}
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$ret);
    }
}
