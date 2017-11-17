<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectItemService;

/**
 * 一招课程列表
 */
class CurriculumAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';

    public function run() {
        $request = Yii::$app->request;
        $subjectid = $request->get('subjectid');
        //分页列表
        $data = VideoSubjectItemService::getByPage($subjectid);
        return $this->controller->render('curriculum', $data);
    }

}
