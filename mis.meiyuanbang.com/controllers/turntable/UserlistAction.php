<?php

namespace mis\controllers\turntable;

use Yii;
use mis\components\MBaseAction;
use mis\service\TurntableGameService;

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
        $title = $request->get('title');
        $time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $type = $request->get('type');
        $data = TurntableGameService::getUserListData($activityid, $time, $end_time, $type,$title);
        $data['activityid'] = $activityid;
        $data['start_time'] = $time;
        $data['end_time'] = $end_time;
        $data['type'] = $type;
        $data['title'] = $title;
        $data['titlelist'] = TurntableGameService::getTitle($activityid);
        return $this->controller->render('userlist', $data);
    }

}
