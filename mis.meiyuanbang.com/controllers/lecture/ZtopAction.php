<?php

namespace mis\controllers\lecture;

use Yii;
use mis\components\MBaseAction;
use mis\service\LectureService;

/**
 * 精讲置顶操作
 */
class ZtopAction extends MBaseAction {

    public $resource_id = 'operation_lecture';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $newsid = $request->post('newsid');
        $value = $request->post('value');
        if (!$newsid || !is_numeric($newsid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = LectureService::findOne(['newsid' => $newsid]);
        if ($model) {
            if ($value == 0) {
                $model->stick_date = 0;
            } else if ($value == 1) {
                $model->stick_date = time();
            }
            $ret = $model->save();
           
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
