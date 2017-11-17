<?php

namespace mis\controllers\publish;

use Yii;
use mis\components\MBaseAction;
use mis\service\PosidHomeUserService;

/**
 * 删除广告方法
 */
class DeladvertAction extends MBaseAction {

    public $resource_id = 'operation_publish';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $uid = $request->post('uid');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = PosidHomeUserService::findOne(['posidid' => $uid]);
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
