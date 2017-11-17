<?php

namespace mis\controllers\turntable;

use Yii;
use mis\components\MBaseAction;
use mis\service\TurntablePrizeService;

/**
 * 活动列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取活动列表
        $data = TurntablePrizeService::getByPage();
        return $this->controller->render('index', $data);
    }

}
