<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkPaperPicService;

/**
 * 分档统计列表
 */
class StatisticalAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_simulation';

    public function run() {
        # SumiaoClickid
       $request = Yii::$app->request;
        //获取各个分档列表
        $data = LkPaperPicService::statisticalData($request->get('SumiaoClickid'));
        return $this->controller->render('statistical', ['data'=>$data]);
    }

}
