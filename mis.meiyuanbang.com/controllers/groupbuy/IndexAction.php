<?php

namespace mis\controllers\groupbuy;

use Yii;
use mis\components\MBaseAction;
use mis\service\GroupbuyService;

/**
 * 团购列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        $request = Yii::$app->request;
        $search = $request->get('search');
        $start_time = strtotime($request->get('start_time'));
        $end_time = strtotime($request->get('end_time'));
        //团购状态
        $status = $request->get('status');
        $data = [];
        if ($search) {
            //分页获取活动列表
            $data = GroupbuyService::getByPage($start_time, $end_time);
        }

        return $this->controller->render('index', $data);
    }

}
