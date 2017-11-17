<?php

namespace mis\controllers\capacity;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;
use common\service\dict\BookDictDataService;
use common\service\DictdataService;
use common\service\dict\CapacityModelDictDataService;

/**
 * 获取二级分类
 */
class SelectMenuAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_capacity_material';

    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = $request->post('f_catalog_id');
        $type = $request->post('type');

        #帖子
        if($type==1){
            $data = DictdataService::getTweetSubType();
        }else{
            #素材
              $data = CapacityModelDictDataService::getCorrectSubType();
        }
        
        $array = [];
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
