<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationActivityService;
use common\service\DictdataService;

/**
 * 邀请活动列表
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取活动列表
        $data = InvitationActivityService::getByPage();
        return $this->controller->render('index', $data);
    }

}
