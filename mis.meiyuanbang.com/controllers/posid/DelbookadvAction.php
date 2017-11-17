<?php

namespace mis\controllers\posid;

use Yii;
use mis\components\MBaseAction;
use common\models\myb\RecommendBookAdv;

/**
 * 删除/排序 出版社管理
 */
class DelbookadvAction extends MBaseAction {

    public $resource_id = 'operation_posid';

    /**
     * 只支持post删除
     */
    public function run() {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            die('非法请求!');
        }
        //检查参数是否非法
        $advid = $request->post('advid');
        $status = $request->post('status');
        # 0=>delete 1=>listorderid 排序
        $type = $request->post('type');
        if (!$advid || !is_numeric($advid)) {
            die('参数不正确');
        }
        
        //根据id取出数据
        $model = RecommendBookAdv::findOne(['advid' => $advid, 'uid' => -1]);
        if ($model) {
            if ($type == 1) {
                $model->listorder = $status;
                $ret = $model->save();
            } else if ($type == 0) {
                $ret = RecommendBookAdv::deleteAll(['advid' => $advid, 'uid' => -1]);
            }
            if ($ret) {
                return $this->controller->outputMessage(['errno' => 0]);
            }
        }
        return $this->controller->outputMessage(['errno' => 1, 'msg' => '删除失败']);
    }

}
