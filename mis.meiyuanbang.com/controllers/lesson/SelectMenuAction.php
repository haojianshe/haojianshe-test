<?php

namespace mis\controllers\lesson;

use Yii;
use mis\components\MBaseAction;
use common\service\yj\DictDataService;

/**
 * 获取二级分类
 */
class SelectMenuAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_capacity_material';

    public function run() {
        $request = Yii::$app->request;
        $course_type = $request->post('course_type');
        $data = DictDataService::getCoursePriceList();
        $array = [];
        $str = '';
        foreach ($data as $key => $val) {
            if ($course_type == $key) {
                foreach ($val as $k => $v) {
                    $str .= "<option value=" . $v['courseid'] . ">" .$v['courseName'].';'.$key."分钟".'、单节:'.$v['coursePrice']. "元</option>";
                }
            }
        }
        echo json_encode($str);
        exit;
    }

}
