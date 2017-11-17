<?php

namespace mis\controllers\invitation;

use Yii;
use mis\components\MBaseAction;
use mis\service\InvitationActivityService;

/**
 * mis用户删除action
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
        $invitation_id = $request->post('invitation_id');

        if (!$invitation_id || !is_numeric($invitation_id)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = InvitationActivityService::findOne(['invitation_id' => $invitation_id]);
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
