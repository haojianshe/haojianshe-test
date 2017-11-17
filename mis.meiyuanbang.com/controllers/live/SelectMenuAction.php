<?php

namespace mis\controllers\live;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;
use common\service\dict\BookDictDataService;
use common\service\dict\LiveDictService;

/**
 * 获取二级分类
 */
class SelectMenuAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    public $resource_id = 'operation_video';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = $request->post('f_catalog_id');

        $data = LiveDictService::getCorrectSubType();
        $str = '';
        foreach ($data as $key => $val) {
            if ($f_catalog_id == $key) {
                foreach ($val as $k => $v) {
                    $str .= "<option value=$k>$v</option>";
                }
            }
        }
        echo json_encode($str);
        exit;
    }

}
