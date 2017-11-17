<?php

namespace mis\controllers\live;

use mis\components\MBaseAction;
use mis\service\LiveRecommendService;

//use common\service\DictdataService;
/**
 * 直播列表
 */
class AddrecommendAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_video';

    public function run() {
        //分页获取直播列表
        $data = LiveRecommendService::getByPageList();
        return $this->controller->render('addrecommend', $data);
    }

}
