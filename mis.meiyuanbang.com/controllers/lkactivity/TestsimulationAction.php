<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\LkActivityService;
use mis\service\MybLkPaperService;
use mis\service\LkPaperPicService;

/**
 * 模拟考试批卷
 */
class TestsimulationAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_simulation';

    public function run() {
        $request = Yii::$app->request;
        if($request->get('cityid')){
             $_SESSION['cityid'] = $request->get('cityid');
        }
        //分页获取模拟考试批卷
        $type = $request->get('type');
        if (!isset($type)) {
            $type = 1;
        }
        $fendang = $request->get('fendang');
        if (!isset($fendang)) {
            $fendang = 10;
        }
        $dangid = $request->get('dangid');
        if (!isset($dangid)) {
            $dangid = 1;
        }
        $daf = $request->get('daf');
        if (isset($daf)) {
            $daf = 1;
        }
        $choose = $request->get('choose');
        if (isset($choose)) {
            $setPrice = LkPaperPicService::setPrice($choose, $request->get('status'));
        }
        $data = LkPaperPicService::getByPage($type, $dafentype, $fendang, $dangid, $daf);
        return $this->controller->render('testsimulation', $data);
    }

}
