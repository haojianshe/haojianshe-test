<?php

namespace mis\controllers\material;

use Yii;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;

/**
 * 
 */
class SortAction extends MBaseAction {

    public $resource_id = 'operation_material';

    public function run() {
        //排序
        $request = Yii::$app->request;
        $subjectid = $request->get('subjectid');
        $data = MatreialSubjectService::getSubject($subjectid);
        $data['models'] = $data;
        return $this->controller->render('sort', $data);
    }

}
