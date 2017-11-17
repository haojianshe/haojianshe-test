<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectService;

/**
 * 一招课程列表
 */
class SubjectAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';

    public function run() {
        //分页列表
        $data = VideoSubjectService::getByPage();
        return $this->controller->render('subject', $data);
    }

}
