<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MsService;

/**
 * 联考名师列表页
 */
class MsAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        //分页获取联考文章列表页
        $hiddenids = $request->get('hiddenids');
        $data = MsService::getByPage($request->get('lkid'),$request->get('hiddenids'));
        return $this->controller->render('qa', $data);
    }

}
