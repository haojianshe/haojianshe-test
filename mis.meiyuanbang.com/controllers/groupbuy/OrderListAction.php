<?php

namespace mis\controllers\groupbuy;

use Yii;
use mis\components\MBaseAction;
use mis\service\OrderinfoService;

/**
 * 团购列表页
 */
class OrderListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        $groupbuyid = $request->get('groupbuyid');
        //分页获取活动列表
        $data = OrderinfoService::getGroupBuy($groupbuyid);
        return $this->controller->render('order_list', $data);
    }

}
