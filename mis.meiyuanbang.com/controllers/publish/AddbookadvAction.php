<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PublishingBookService;

/**
 * 出版社添加推荐
 */
class AddbookadvAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $type = $request->get('type');
        //分页获取广告位列表
        $data = PublishingBookService::getUserBookList($uid,$type);
        return $this->controller->render('addbookadv', $data);
    }

}
