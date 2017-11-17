<?php

namespace mis\controllers\reward;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkPrizesService;

/**
 * 活动列表页
 */
class UserlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        
        //分页获取活动列表
        $request = Yii::$app->request;
        //检查参数是否非法
        $activityid = $request->get('activityid');
        $time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $type = $request->get('type');
        $data = DkPrizesService::getUserListData($activityid,$time,$end_time,$type);
        return $this->controller->render('userlist', $data);
    }

}
