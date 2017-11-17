<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;
use common\service\DictdataService;

/**
 * 专题副标题列表
 */
class AddtagAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_lecture';

    public function run() {
        $request = Yii::$app->request;
        $newsid = trim($request->get("newsid"));
        $lecture_tagid= trim($request->get("lecture_tagid"));
     
        //分页获取精讲列表
        $data = LectureService::getAddtagPage($newsid,$lecture_tagid);
        return $this->controller->render('addtag', $data);
    }

}
