<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\RecommendBookService;

/**
 * 美院帮图书推荐 
 */
class MybbookAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_posid';

    public function run() {
        $request = Yii::$app->request;
        $type = $request->get('type');
        if (!isset($type)) {
            $type = 1;
        }
        //分页获取图书列表
        $data = RecommendBookService::getMybBookList($type);
        return $this->controller->render('mybbook', $data);
    }

}
