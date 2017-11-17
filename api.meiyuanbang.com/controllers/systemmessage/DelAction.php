<?php
namespace api\controllers\systemmessage;

use Yii;
use api\components\ApiBaseAction;
use api\lib\enumcommon\ReturnCodeEnum;
use api\service\SystemMessageService;

/**
 * 删除通知
 */
class DelAction extends ApiBaseAction
{
    public function run()
    {
        $sys_msg_id = intval($this->requestParam('sys_msg_id'));
        $model=SystemMessageService::findOne(['sys_message_id'=>$sys_msg_id]);
        if($model->to_uid != $this->_uid){
            $data['message']='No permissions';
            $this->controller->renderJson(ReturnCodeEnum::STATUS_OK,$data);
            exit;
        }
        $model->is_del=1;
        $model->save();
        $this->controller->renderJson(ReturnCodeEnum::STATUS_OK);
    }
}
