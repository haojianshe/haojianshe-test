<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\MybLkPaperService;

/**
 * 删除活动方法
 */
class DelpicAction extends MBaseAction {

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
        $paperid = $request->post('paperid');
        $status = $request->post('status');
        if (!$paperid || !is_numeric($paperid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = MybLkPaperService::findOne(['paperid' => $paperid]);
        if ($model) {
            $model->status = 2;
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
