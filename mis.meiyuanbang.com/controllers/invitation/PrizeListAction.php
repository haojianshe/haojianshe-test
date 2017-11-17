<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationPrizesService;

/**
 * 邀请活动列表 奖品列表
 */
class PrizeListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取活动列表
         $data = InvitationPrizesService::getByPage();
         return $this->controller->render('prize_list',$data);
    }

}
