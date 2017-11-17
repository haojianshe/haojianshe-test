<?php

namespace mis\controllers\material;

use Yii;
use mis\components\MBaseAction;
use mis\service\MatreialSubjectService;

/**
 * 删除专题
 */
class DelAction extends MBaseAction {

    public $resource_id = 'operation_activity';

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
        $type = $request->post('type');
        if (!$subjectid || !is_numeric($subjectid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = MatreialSubjectService::findOne(['subjectid' => $subjectid]);
        $redis = Yii::$app->cache;
        if ($model) {
            if ($type == 3) {
                $model->status = 1;
            } elseif ($type == 1) {
                $model->stick_date = time();
            } elseif ($type == 0) {
                $model->stick_date = null;
            }elseif ($type == 4) {
                $model->status = 0;
            }
            $ret = $model->save();
            $redis->delete("subject_list");
            $redis->delete("subject_list" . $model->subject_typeid);
            $redis->delete("material_subject_detail" . $model->subjectid);
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
