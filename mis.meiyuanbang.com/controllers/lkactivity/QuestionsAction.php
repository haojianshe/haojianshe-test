<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\QaService;

/**
 * 问答列表页面
 */
class QuestionsAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取联考活动列表
        $data = QaService::getQuestionsData();
        return $this->controller->render('questions', $data);
    }

}
