<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\RecommendBookService;

/**
 * 出版社添加推荐
 */
class AddbookadvAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_posid';

    public function run() {
        $request = Yii::$app->request;
        $type = $request->get('type');
        $uid = $request->get('uid');
        if (!isset($uid)) {
            $uid = 0;
        }
        //分页获取广告位列表
        $data = RecommendBookService::getUserBookList($type,$uid);
        return $this->controller->render('addbookadv', $data);
    }

}
