<?php

namespace mis\controllers\user;

use Yii;
use mis\components\MBaseAction;
use mis\service\YjUserService;

/**
 * 学员列表
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_zhn';

    public function run() {
        $request = Yii::$app->request;
        $search['is_sign'] = $request->get('is_sign');
        $search['start_time'] = strtotime($request->get('start_time'));
        $search['end_time'] = strtotime($request->get('end_time'));
        //用户名
        $search['user_name'] = $request->get('user_name');
        //分页获取用户列表
        $data = YjUserService::getByPage($search);
        $data['is_sign'] = $search['is_sign'];
        $data['start_time'] = $request->get('start_time');
        $data['end_time'] = $request->get('end_time');
        $data['user_name'] = $search['user_name'];
        return $this->controller->render('index', $data);
    }

}
