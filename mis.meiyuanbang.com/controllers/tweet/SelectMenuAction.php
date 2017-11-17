<?php

namespace mis\controllers\tweet;

use Yii;
use mis\components\MBaseAction;
//use mis\service\PosidHomeUserService;
//use common\service\dict\BookDictDataService;
//use common\service\dict\LiveDictService;
use common\service\DictdataService;

/**
 * 获取二级分类
 */
class SelectMenuAction extends MBaseAction {


    public function run() {
        $request = Yii::$app->request;
        $f_catalog_id = $request->post('f_catalog_id');
        $tag = $request->post('tag');
        $data = DictdataService::getTweetSubType();
        $array = [];
        if($tag){
              $str = '<option value=0>请选择</option>';
        }else{
              $str = '<option>请选择</option>';
        }
      
        foreach ($data as $key => $val) {
            if ($f_catalog_id == $key) {
                foreach ($val as $k => $v) {
                    if($tag){
                         $str .= "<option value=$k>$v</option>";
                    }else{
                         $str .= "<option value=$v>$v</option>";
                    }
                   
                }
            }
        }
        echo json_encode($str);
        exit;
    }

}
