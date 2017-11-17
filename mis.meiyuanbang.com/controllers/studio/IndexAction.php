<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioService;

/**
 * 列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';
    public function run() {
        
        //分页列表
        $data = StudioService::getByPage();
        return $this->controller->render('index', $data);
    }

}
