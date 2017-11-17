<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectService;
use mis\service\VideoSubjectItemService;

//use mis\service\UserService;

/**
 * 选择课程
 */
class RecommendAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_course';

    public function run() {
        $request = Yii::$app->request;
        $subjectid = $request->get('subjectid');
        //获取已经存在的课程id
        $videoArray = VideoSubjectItemService::getCoures($subjectid);
        //获取所有的课程
        $data = VideoSubjectItemService::getCouresList($subjectid,1);
        if ($videoArray) {
            foreach ($data['models'] as $key => $val) {
                foreach ($videoArray as $k => $v) {
                    if ($val['courseid'] == $v) {
                        $data['models'][$key]['type']=1;
                    }
                }
            }
        }
       # print_r($videoArray);
        $str = implode(',', $videoArray);
        return $this->controller->render('recommend', ['models'=>$data,'videostr'=>$str]);
    }

}
