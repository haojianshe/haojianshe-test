<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use common\service\DictdataService;
/**
 * 获取二级分类
 */
class SelectMenuAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_capacity_material';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = $request->post('f_catalog_id');

        $data = DictdataService::getLectureSubType($f_catalog_id);
        $array = [];
        $str = '';
        foreach ($data as $key => $val) {
            $str .= "<option value=" . $val['subtypeid'] . ">" . $val['subtypename'] . "</option>";
        }
        echo json_encode($str);
        exit;
    }

}
