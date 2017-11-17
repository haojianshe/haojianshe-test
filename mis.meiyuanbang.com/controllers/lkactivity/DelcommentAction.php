<?php

namespace mis\controllers\lkactivity;

use Yii;
use mis\components\MBaseAction;
use mis\service\CommentService;

/**
 * 删除文章评论方法
 */
class DelcommentAction extends MBaseAction {

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
        $cid = $request->post('cid');
        $is_del = $request->post('is_del');
        if (!$cid || !is_numeric($cid)) {
            die('参数不正确');
        }
        //根据id取出数据
        $model = CommentService::findOne(['cid' => $cid]);
        if ($model) {
            $model->is_del = $is_del;
            $ret = $model->save();
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
