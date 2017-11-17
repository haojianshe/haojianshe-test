<?php
namespace api\controllers\info;

use Yii;
use api\components\ApiBaseAction;
use api\service\MessageService;
use api\service\SystemMessageService;
use api\service\CorrectService;
use api\service\UserCouponService;
use api\service\GroupBuyService;
use api\lib\enumcommon\ReturnCodeEnum;
use common\service\DictdataService;

/**
 * 启动时，获取最新消息数,包括私信、系统消息、批改消息
 */
class MsgNumAction extends ApiBaseAction
{   
    public function run()
    { 
    	//获取当前uid
    	if($this->_uid == -1){
    		$this->_uid = $this->requestParam('uid',true);
    	}
    	$ret = [];
        //(1)获取新私信信息
    	$ret['private_msg'] = MessageService::getNewMsgInfo($this->_uid);
    	//(2)新系统通知数
    	$ret['sys_msg']['num'] = SystemMessageService::getNewMsgCount($this->_uid);
    	//(3)新批改数量(兼容老版本)
    	$ret['correct_msg']['num'] = CorrectService::getNewCorrectNum($this->_uid);
        //新版本对应分类返回数量
    	$catalog=DictdataService::getCorrectTypeAndTag();
        foreach ($catalog['data'] as $key => $value) {
            $ret['correct_msg']['num_'.$value['subid']]=CorrectService::getNewCorrectNum($this->_uid,$value['subid']);
        }
        //(4)获取新课程卷数量
        $ret['coupon_msg']['num'] = UserCouponService::getNewCouponNum($this->_uid);
        //(4)获取团购数量
        $ret['groupbuy_msg']['num'] = GroupBuyService::getNewGroupBuyNum($this->_uid);
        
    	$this->controller->renderJson(ReturnCodeEnum::STATUS_OK, $ret);
    }
}
