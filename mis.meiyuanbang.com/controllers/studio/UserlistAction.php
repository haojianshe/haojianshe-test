<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioMenuService;

/**
 * 列表页
 */
class UserlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {

        //分页列表
        $request = Yii::$app->request;
        //检查参数是否非法
        $uid = $request->get('uid');
        $sname = $request->get('sname');
        $data = StudioMenuService::getByPage($uid, $sname);
        return $this->controller->render('userlist', $data);
    }

}
