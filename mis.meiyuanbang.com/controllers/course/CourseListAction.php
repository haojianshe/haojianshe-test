<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\CourseService;

/**
 * 分成课程列表
 */
class CourseListAction extends MBaseAction {

    public function run() {
        $request = Yii::$app->request;
       //get访问，判断是edit还是add,返回不同界面
        $title= $request->get('title');
        $courseid= $request->get('courseid');
        $id= $request->get('id');
        //分页课程列表
        $data = CourseService::getCourseByPage($title,$courseid);
        $data['title'] = $title;
        $data['courseid'] = $courseid;
        $data['id'] = $id;
        return $this->controller->render('course_list', $data);
    }

}
