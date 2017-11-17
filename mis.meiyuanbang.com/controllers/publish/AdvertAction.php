<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;

/**
 * 广告位管理
 */
class AdvertAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_publish';

    public function run() {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        //分页获取广告位列表
        $data = PosidHomeUserService::getUserAdvert($uid);
        return $this->controller->render('advert', $data);
    }

}
