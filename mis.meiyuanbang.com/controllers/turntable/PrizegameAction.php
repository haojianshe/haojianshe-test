<?php

namespace mis\controllers\turntable;

use Yii;
use mis\components\MBaseAction;
use mis\service\TurntableGameService;

/**
 * 活动列表页
 */
class PrizegameAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取活动列表
        $data = TurntableGameService::getByPage();
        return $this->controller->render('prizegame', $data);
    }

}
