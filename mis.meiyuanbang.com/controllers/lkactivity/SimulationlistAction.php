<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybLkPaperService;
use mis\service\LkActivityService;

/**
 * 模拟考试批卷城市列表
 */
class SimulationlistAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_simulation';

    public function run() {
    
        $data = MybLkPaperService::CityList();
        return $this->controller->render('simulationlist', ['data'=>$data]);
    }

}
