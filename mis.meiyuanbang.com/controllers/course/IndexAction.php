<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseService;

/**
 * 列表页
 */
class IndexAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = trim($request->get("f_catalog_id")); #主分类
        $s_catalog_id = trim($request->get("s_catalog_id")); #二级分类
        $title = trim($request->get("title")); #标题
        $start_time = trim($request->get("start_time")); #开始时间
        $end_time = trim($request->get("end_time")); #结束时间
        //分页列表
        $data = CourseService::getByPage($f_catalog_id, $s_catalog_id, '', $title, $start_time, $end_time);
        
        $moedls = $data['models'];
        $data['models'] = $moedls;
        $data['title'] = $title;
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
        $data['f_catalog_id'] = $f_catalog_id;
        $data['s_catalog_id'] = $s_catalog_id;
        $data['courseCanSum'] =  CourseService::getCourseCanNum($f_catalog_id, $s_catalog_id, $title, $start_time, $end_time);
        return $this->controller->render('index', $data);
    }

}
