<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationAwardRecordService;

/**
 * 邀请活动列表 邀请记录列表
 */
class AwardRecordAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
          $request = Yii::$app->request;
           $invitation_id = $request->get('invitation_id');
          
        //分页获取活动列表
         $data = InvitationAwardRecordService::getByPage($invitation_id);
         return $this->controller->render('award_record',$data);
    }

}
