<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\UserService;

/**
 * 联考列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_publish';

    public function run() {
        //分页获取联考活动列表
        $data = UserService::getPublish();
        return $this->controller->render('index', $data);
    }

}
