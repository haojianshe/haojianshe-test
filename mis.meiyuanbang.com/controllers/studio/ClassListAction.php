<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioClasstypeService;

/**
 * 不同画室不同班型列表页
 */
class ClassListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {
        //分页列表
        $request = Yii::$app->request;
        $studiomenuid = $request->get('studiomenuid');
        $uid = $request->get('uid');
        $data = StudioClasstypeService::getByPage($studiomenuid, $uid);
        return $this->controller->render('class_list', ['models' => $data]);
    }

}
