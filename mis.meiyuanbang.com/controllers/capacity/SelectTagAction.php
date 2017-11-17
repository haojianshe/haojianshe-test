<?php

namespace mis\controllers\capacity;

use Yii;
use mis\components\MBaseAction;
//use mis\service\PosidHomeUserService;
//use common\service\dict\BookDictDataService;
use common\service\DictdataService;
use mis\service\MatreialSubjectService;
use mis\service\CapacityModelMaterialService;
use mis\models\Tweet;

#use mis\service\Tweet;

/**
 * 获取二级分类选中的标签列表
 */
class SelectTagAction extends MBaseAction {

    //在配置文件中配置的resource对应的参数名字
    //public $resource_id = 'operation_capacity_material';

    public function run() {
        $request = Yii::$app->request;
        $s_catalog_id = $request->post('s_catalog_id');
        $materialid = $request->post('materialid');
        $type = $request->post('type');
        $f_catalog = $request->post('f_catalog');
        if ($type == 1) {
            $model = CapacityModelMaterialService::findOne(['materialid' => $materialid, 's_catalog_id' => $s_catalog_id]);
            $s_catalog_ids = $s_catalog_id;
        } else {
            foreach (DictdataService::getTweetSubType() as $key => $val) {
                foreach ($val as $k => $v) {
                    if ($v == $s_catalog_id) {
                        $s_catalog_ids = $k;
                    }
                }
            }
            $model = Tweet::findOne(['tid' => $materialid, 's_catalog_id' => $s_catalog_ids]);
        }
        $tagList = MatreialSubjectService::getTag($type, $s_catalog_ids, $model->tags);
        echo json_encode($tagList);
        exit;
    }

}
