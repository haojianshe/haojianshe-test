<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use mis\service\RecommendBookService;

/**
 * 能力模型图书推荐 
 */
class AbilitybookAction  extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_posid';

    public function run() {
       $request = Yii::$app->request;
        $type = $request->get('type');
        if (!isset($type)) {
            $type = 0;
        }
        //分页获取图书列表
        $data = RecommendBookService::getMybRecommendBoookList($type);
        return $this->controller->render('abilitybook', $data);
    }

}
