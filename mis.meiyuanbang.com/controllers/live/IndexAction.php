<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\LiveService;

//use common\service\DictdataService;
/**
 * 直播列表
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_video';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = trim($request->get("f_catalog_id")); #主分类
        $s_catalog_id = trim($request->get("s_catalog_id")); #二级分类
        $title = trim($request->get("title")); #标题
        $start_time = trim($request->get("start_time")); #开始时间
        $end_time = trim($request->get("end_time")); #结束时间
        //分页获取直播列表
        $data = LiveService::getByPage($f_catalog_id, $s_catalog_id, $title, $start_time, $end_time);
        
        $moedls = $data['models'];
        $data['models'] = $moedls;
        $data['title'] = $title;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['s_catalog_id'] = $s_catalog_id;
        $data['liveCanSum'] =  LiveService::getLiveCanNum($f_catalog_id, $s_catalog_id, $title, $start_time, $end_time);
        return $this->controller->render('index', $data);
    }

}
