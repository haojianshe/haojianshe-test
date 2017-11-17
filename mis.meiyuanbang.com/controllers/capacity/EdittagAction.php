<?php

namespace mis\controllers\capacity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;

/**
 * 公共页面
 */
class EdittagAction extends MBaseAction {

    public $resource_id = 'operation_capacity_material';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        //检查参数是否非法
        $id = $request->get('id');
        $type = $request->get('type');
        if ($request->isPost) {
            print_r($request->post());
        }
        $tagList = MatreialSubjectService::getTag(11336, 1);
        return $this->controller->render('edittag', ['model' => $tagList]);
    }

}
