<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;

/**
 * 画室广告页列表
 */
class AdvertlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';

    public function run() {
        $request = Yii::$app->request;
        //先获取model
        $model = new PosidHomeUserService();
        $uid = $request->get('uid');
        //分页列表
        $data = PosidHomeUserService::getByPage($uid);
        return $this->controller->render('advertlist', $data);
    }

}
