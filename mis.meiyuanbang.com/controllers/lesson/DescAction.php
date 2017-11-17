<?php

namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use mis\service\LessonDescService;
use common\service\DictdataService;

/**
 * 跟着画描述列表
 */
class DescAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lesson';

    public function run() {
        $request = Yii::$app->request;
        $lessonid = trim($request->get("lessonid")); #lessonid
        $data = LessonDescService::getByPage($lessonid);
        $data['lessonid']=$lessonid;
        return $this->controller->render('desc', $data);
    }

}
