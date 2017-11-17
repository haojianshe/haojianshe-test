<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\QaService;

/**
 * 联考问答列表页
 */
class QaAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //分页获取联考文章列表页
        $lkid = $request->get('lkid');
        $data = QaService::getByPage($lkid);
        return $this->controller->render('qa', $data);
    }

}
