<?php

namespace mis\controllers\user;

use Yii;
use mis\components\MBaseAction;
use mis\service\YjUserService;

/**
 * 审核
 */
class AuditAction extends MBaseAction {

    public $resource_id = 'operation_zhn';

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
        $type = $request->post('type');
        if (!$uid || !is_numeric($uid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = YjUserService::findOne(['uid' => $uid]);
        if ($model) {
            if ($type == 1) {
                $model->status = $type;
            } else {
                $model->status = $type;
            }

            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '操作失败']);
    }

}
