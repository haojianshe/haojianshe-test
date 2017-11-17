<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PublishingBookService;

/**
 * 图书列表
 */
class BookmanageAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        //分页获取图书列表
       
        $data = PublishingBookService::getUserListBook($uid);
        return $this->controller->render('bookmanage', $data);
    }

}
