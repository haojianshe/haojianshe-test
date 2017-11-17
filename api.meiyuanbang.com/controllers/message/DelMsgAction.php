<?php
namespace api\controllers\message;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\MessageService;
use common\models\myb\Message;

/**
 * 在私信列表里删除一个会话
 */
class DelMsgAction extends ApiBaseAction
{
    public function run()
    {
    	//对话人id
    	$otheruid = $this->requestParam('msg_uid',true);
    	//数据库更新删除标志
    	MessageService::delTalk($this->_uid, $otheruid);
    	//清除小红点
    	MessageService::removeRed($this->_uid, $otheruid);
    	    	
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }
}
