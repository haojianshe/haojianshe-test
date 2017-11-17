<?php

namespace mis\controllers\reward;

use Yii;
use mis\components\MBaseAction;
use mis\service\DkPrizesService;

/**
 * 奖品逻辑删除
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
        $prizesid = $request->post('prizesid');
        if (!$prizesid || !is_numeric($prizesid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = DkPrizesService::findOne(['prizesid' => $prizesid]);
        if ($model) {
            $model->status = 0;
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
