<?php

namespace mis\controllers\studio;

use Yii;
use mis\components\MBaseAction;
use mis\service\StudioAddressService;

/**
 * 地址列表页
 */
class EditaddressListAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_studio';
    public function run() {
        $request = Yii::$app->request;

        $uid = $request->get('uid');
        
        //分页列表
        $data = StudioAddressService::getByPage($uid);
        return $this->controller->render('editaddress_list', $data);
    }

}
