<?php

namespace mis\controllers\course;

use Yii;
use mis\components\MBaseAction;
use mis\service\VideoSubjectService;

/**
 * 删除 审核 一招
 */
class OperationAction extends MBaseAction {

    public $resource_id = 'operation_course';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $subjectid = $request->post('subjectid');
        $status = $request->post('status');
        if (!$subjectid || !is_numeric($subjectid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = VideoSubjectService::findOne(['subjectid' => $subjectid]);
        if ($model) {
            $model->status = $status;
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
