<?php

namespace mis\controllers\turntable;

use Yii;
use mis\components\MBaseAction;
use mis\service\TurntablePrizeService;

/**
 * 活动列表页
 */
class RewardlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_activity';

    public function run() {
        //分页获取活动列表
        $data = TurntablePrizeService::getByPage();
        $data['v'] = $request = Yii::$app->request->get('v');
        $data['i'] = $request = Yii::$app->request->get('i');
        $data['id'] = $request = Yii::$app->request->get('id');
       
        return $this->controller->render('rewardlist', $data);
    }
  

}
